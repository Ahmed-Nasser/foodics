<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use App\Models\IngredientProduct;
use App\Models\Order;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\New_;
use Tests\TestCase;
use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_the_user_can_not_create_invalid_order(): void
    {
        $response = $this
            ->json('POST', 'api/v1/orders', ['products' => ['productId' => 1]]);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => []
        ]);
    }

    public function test_the_user_can_create_order()
    {
        $data = $this->prepareOrderProducts();

        $response = $this->json('POST', 'api/v1/orders', ['products' => $data]);

        $response->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ]);

        $latestOrder = Order::latest()->first();

        $this->assertDatabaseHas('orders', [
           'id' => $latestOrder->id,
           'merchant_id' => $latestOrder->merchant_id,
           'status' => 'in-progress',
        ]);

        $products = $latestOrder->products();
        dd($products->first()->Ingredients()->get()->toArray());
        $relatedProducts = $products->getRelated();

        $this->assertInstanceOf(Product::class, $relatedProducts);

        $this->assertTrue($latestOrder->products()->exists());
    }

    private function prepareOrderProducts(): array
    {
        $ingredients = Ingredient::factory(5)
            ->create()->pluck('id')->toArray();

        $products = Product::factory(3)
            ->create();

        foreach ($products as $product){
            for ($i = 0; $i < 3; $i++){
                IngredientProduct::factory()->create([
                   'product_id'    => $product->id,
                   'ingredient_id' => $ingredients[Arr::random([0,1,2])],
                ]);
            }
        }

        $ingredientProduct = IngredientProduct::where(function ($query) use ($products, $ingredients){
            $query->whereIn('ingredient_id',$ingredients)->whereIn('product_id', $products->pluck('id')->toArray());
        })->get();

        foreach ($ingredientProduct as $ingredient){
            if (in_array($ingredient->id, $ingredients)){
                Stock::factory()->create([
                    'ingredient_id' => $ingredient->id,
                ]);
            }
        }

        return $products->map(function ($product){
            $quantity = Arr::random([3, 5, 7]);
            return [
                'productId' => $product->id,
                'quantity' => $quantity,
            ];
        })->toArray();
    }

}
