<?php

namespace App\Http\Requests;

use App\Models\Stock;
use App\Models\IngredientProduct;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'products'             => ['array', 'required'],
            'products.*.productId' => ['required', 'string', 'exists:products,id'],
            'products.*.quantity'  => ['required', 'integer', 'min:1'],
        ];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $products = collect(request('products'));
            $orderIngredients = $this->calculateOrderIngredients($products);
            foreach ($orderIngredients as $ingredientId => $ingredientConsumption){
               $availableIngredient = Stock::where('ingredient_id', $ingredientId)->first()->ingredient_amount;
                if ($availableIngredient < $ingredientConsumption){
                    $validator->errors()->add('quantity', 'Invalid product quantity. Out of stock.');
                }
            }
        });


    }

    private function calculateOrderIngredients(Collection $products): array
    {
        $orderIngredients = [];
        $products->map(function ($product) use (&$orderIngredients) {
            $productId = $product['productId'] ?? '';
            $ingredients = IngredientProduct::where('product_id', $productId)->get();
            $ingredients->map(function ($ingredient) use ($product, &$orderIngredients) {
                if(key_exists($ingredient->ingredient_id, $orderIngredients)){
                    $orderIngredients[$ingredient->ingredient_id] = ($orderIngredients[$ingredient->ingredient_id]) + ($ingredient->ingredient_amount * $product['quantity'] / 1000);
                } else{
                    $orderIngredients[$ingredient->ingredient_id] = ($ingredient->ingredient_amount) * ($product['quantity'] / 1000);
                }
            });
        });
        return $orderIngredients;
    }
}
