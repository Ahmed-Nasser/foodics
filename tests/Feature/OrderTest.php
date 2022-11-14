<?php

namespace Tests\Feature;

use Database\Seeders\MerchantSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Ingredient;
use Illuminate\Support\Arr;
use App\Models\IngredientProduct;
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

        $stockTransaction = DB::table('transaction_stock')->get();

        $this->assertDatabaseHas('orders', [
           'id' => $latestOrder->id,
           'merchant_id' => $latestOrder->merchant_id,
           'status' => 'in-progress',
        ]);

        $products = $latestOrder->products();

        $relatedProducts = $products->getRelated();

        $this->assertInstanceOf(Product::class, $relatedProducts);

        $this->assertTrue($latestOrder->products()->exists());

        foreach ($products->get() as $product){
            $ingredients = $product->ingredients();

            $relatedProductIngredients = $ingredients->getRelated();

            $this->assertInstanceOf(Ingredient::class, $relatedProductIngredients);

            $this->assertTrue($product->ingredients()->exists());

            foreach ($ingredients->get() as $ingredient){
                $transaction = $stockTransaction->where('stock_id', $ingredient->stock->id)->first();
                $oldAmount = $transaction->old_amount;
                $consumedAmount = $transaction->consumed_amount;
                $currentAmount = $ingredient->stock->ingredient_amount;
                $this->assertTrue( ($oldAmount - $consumedAmount) == $currentAmount);
            }
        }
    }

    private function prepareOrderProducts(): array
    {
        $this->seed(MerchantSeeder::class);

        $ingredients = Ingredient::factory(5)
            ->create()->pluck('id')->toArray();

        $products = Product::factory(3)
            ->create();

        foreach ($products as $product){
            for ($i = 0; $i < 3; $i++){
                IngredientProduct::factory()->create([
                    'product_id'    => $product->id,
                    'ingredient_id' => $ingredients[Arr::random([0,1,2])],
                    'ingredient_amount' => fake()->numerify('#####')
                ]);
            }
        }

        $ingredientProduct = IngredientProduct::where(function ($query) use ($products, $ingredients){
            $query->whereIn('ingredient_id',$ingredients)->whereIn('product_id', $products->pluck('id')->toArray());
        })->get();

        foreach ($ingredientProduct as $ingredient){
            if (in_array($ingredient->ingredient_id, $ingredients)){
                Stock::factory()->create([
                    'ingredient_id' => $ingredient->ingredient_id,
                ])->each(function ($stock){
                    DB::table('transaction_stock')->insert([
                        'id' => Str::uuid()->toString(),
                        'stock_id' => $stock->id,
                        'type' => 'credit',
                        'consumed_amount' => 0,
                        'old_amount' => $stock->initial_ingredient_amount
                    ]);
                });
            }
        }

        return $products->map(function ($product){
            $quantity = Arr::random([1, 3, 5]);
            return [
                'productId' => $product->id,
                'quantity' => $quantity,
            ];
        })->toArray();
    }

}
