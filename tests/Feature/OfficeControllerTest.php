<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\Office;
use App\Models\Reservation;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OfficeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example()
    {
        Office::factory(3)->create();
        // dd("creted");
        $response = $this->get('/api/offices');

        $response->assertStatus(200);

        $this->assertNotNull($response->json('data')[0]['id']);
        $this->assertCount(3,$response->json('data'));
        $response->assertJsonCount(3,'data');
        $this->assertNotNull($response->json('meta'));

    }

    public function testItFilterByhHostId()
    {
        Office::factory(3)->create();
        $host  = User::factory()->create();
        $office = Office::factory()->for($host)->create();

        $response = $this->get(
            '/api/offices?host_id='.$host->id
        );

        $response->assertOk();
        $response->assertJsonCount(1,'data');
        // $this->assertNotEquals($office->id,$response->json('data')[0]['id']);
        $this->assertEquals($office->id,$response->json('data')[0]['id']);
    }

    public function testItFilterByUserId()
    {
        Office::factory()->create();
        $user = User::factory()->create();
        $office = Office::factory()->create();

        Reservation::factory()->for(Office::factory()->create());
        Reservation::factory()->for($office)->for($user)->create();

        
        $response = $this->get(
            '/api/offices?user_id='.$user->id
        );

        $response->assertOk();
        $response->assertJsonCount(1,'data');
        // $this->assertNotEquals($office->id,$response->json('data')[0]['id']);
        $this->assertEquals($office->id,$response->json('data')[0]['id']);
    }

    public function testItIncludeImagesTagsUser()
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create();
        
        


        $office = Office::factory()->for($user)->create();
        $office->tags()->attach($tag);
        

        $office->images()->create(['path'=>'image.jpg']);
        $response = $this->get('/api/offices');

        $response->assertOk() ;
        $this->assertIsArray($response->json('data')[0]['tags']);
        $this->assertCount(1,$response->json('data')[0]['tags']);
        $this->assertIsArray($response->json('data')[0]['images']);
        $this->assertCount(1,$response->json('data')[0]['images']);
        $this->assertEquals($user->id, $response->json('data')[0]['user']['id']);
        
    }

    public function testItCheckNumberOfActiveReservation()
    {
        $office = Office::factory()->create();

        Reservation::factory()->for($office)->create(['status'=>Reservation::STATUS_ACTIVE]);
        Reservation::factory()->for($office)->create(['status'=>Reservation::STATUS_CANCELLED]);
        $response = $this->get('/api/offices');

        $response->assertOk();
        $this->assertEquals(1,$response->json('data')[0]['reservations_count']);
    }

    public function testItOrdersByDistanceWhenCoordinatesAreProvided()
    {
        $office = Office::factory()->create([
            'lat' =>'38.720661384644046',
            'lng' =>'-9.16044783453807',
            'title' => 'Torres Vedras'
        ]);

        $office1 = Office::factory()->create([
            'lat' =>'52.289659437560054',
            'lng' =>'-9.139748148410584',
            'title' => 'Leiria'
        ]);


        $response = $this->get('/api/offices?lat=38.720661384644046&lng=-9.16044783453807');
        $response->assertOk();
        $this->assertEquals('Torres Vedras',$response->json('data')[0]['title']);
        $this->assertEquals('Leiria',$response->json('data')[1]['title']);
    }

    public function testItShowsTheOffice()
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create();

        $office = Office::factory()->for($user)->create();
        $office->tags()->attach($tag);
        $office->images()->create(['path'=>'image.jpg']);

        // $office = Office::factory()->create();

        Reservation::factory()->for($office)->create(['status'=>Reservation::STATUS_ACTIVE]);
        Reservation::factory()->for($office)->create(['status'=>Reservation::STATUS_CANCELLED]);
    

        $response = $this->get('/api/offices/'.$office->id);
        $this->assertEquals(1,$response->json('data')['reservations_count']);
        $this->assertIsArray($response->json('data')['tags']);
        $this->assertCount(1,$response->json('data')['tags']);
        $this->assertIsArray($response->json('data')['images']);
        $this->assertCount(1,$response->json('data')['images']);
        $this->assertEquals($user->id, $response->json('data')['user']['id']);

    //    dump($response->json('data'));

    }

    
}
