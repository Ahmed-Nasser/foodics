<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_the_user_can_create_order()
    {
        $response = $this->json('POST', 'api/v1/orders');
        dd($response->getStatusCode());
        $response->assertStatus(200);
    }
}
