<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchItemTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testItemSearch()
    {
    	$response = $this->json('POST', '/api/search', [
            'search_term' => str_random(10)
        ]);

        $response->assertStatus(200);
    }

    public function testItemSearchWithPagination()
    {
    	$response = $this->json('POST', '/api/search', [
            'search_term' => str_random(10),
            'goto' => 2
        ]);

        $response->assertStatus(200);
    }
}
