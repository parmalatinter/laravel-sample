<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Item;

class ItemApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_item()
    {
        $item = Item::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/items', $item
        );

        $this->assertApiResponse($item);
    }

    /**
     * @test
     */
    public function test_read_item()
    {
        $item = Item::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/items/'.$item->id
        );

        $this->assertApiResponse($item->toArray());
    }

    /**
     * @test
     */
    public function test_update_item()
    {
        $item = Item::factory()->create();
        $editedItem = Item::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/items/'.$item->id,
            $editedItem
        );

        $this->assertApiResponse($editedItem);
    }

    /**
     * @test
     */
    public function test_delete_item()
    {
        $item = Item::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/items/'.$item->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/items/'.$item->id
        );

        $this->response->assertStatus(404);
    }
}
