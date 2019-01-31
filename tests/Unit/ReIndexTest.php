<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReIndexTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testElasticSearchIndexing()
    {
    	$response = $this->json('DELETE', '/api/library/re-index');

        $response->assertStatus(200);
    }

    public function testElasticSearchIndexLatestChanges()
    {
    	$response = $this->json('PUT', '/api/library/index/latest');

        $response->assertStatus(200);
    }
}
