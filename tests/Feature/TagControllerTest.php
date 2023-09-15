<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/api/tags');
        // dd(
        //     $response
        // );
        $response->assertStatus(200);

        $this->assertCount(3,$response->json('data'));
        $this->assertNotNull($response->json('data')[0]['id']);
    }
}
