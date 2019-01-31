<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Model\Video;
use App\Model\Actor;
use App\Model\Category;
use App\Model\Tag;
use App\Model\VideoActor;
use App\Model\VideoTag;
use App\Model\VideoCategory;

class RecordUpdateTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testViewAllActor()
    {
    	$actor = factory(Actor::class)->make();
        $actor->save();

        $response = $this->json('GET', '/api/library/actors');

        $response->assertStatus(200);
    }

    public function testFilterAllActorWithPagination()
    {
    	$response = $this->json('GET', '/api/filter/actors?goto=2');

        $response->assertStatus(200);
    }

    public function testFilterAllActorWithSearch()
    {
    	$response = $this->json('GET', '/api/filter/actors?search_term=Fame');

        $response->assertStatus(200);
    }

    public function testSelectedActorPreview()
    {
    	$actor = factory(Actor::class)->make();
        $actor->save();

    	$response = $this->json('POST', '/api/filter/actors', [
            'actor_id' => $actor->id
        ]);

        $response->assertStatus(200);
    }

    public function testActorDetailsUpdate()
    {
    	$actor = factory(Actor::class)->make();
        $actor->save();

    	$response = $this->json('POST', '/api/actors/update', [
            'title' => str_random(10)
        ]);

        $response->assertStatus(200);
    }

    public function testViewAllTag()
    {
    	$tag = factory(Tag::class)->make();
        $tag->save();

        $response = $this->json('GET', '/api/library/tags');

        $response->assertStatus(200);
    }

    public function testFilterAllTagWithPagination()
    {
    	$response = $this->json('GET', '/api/filter/tags?goto=2');

        $response->assertStatus(200);
    }

    public function testFilterAllTagWithSearch()
    {
    	$response = $this->json('GET', '/api/filter/tags?search_term=qui');

        $response->assertStatus(200);
    }

    public function testSelectedTagPreview()
    {
    	$tag = factory(Tag::class)->make();
        $tag->save();

    	$response = $this->json('POST', '/api/filter/tags', [
            'tag_id' => $tag->id
        ]);

        $response->assertStatus(200);
    }

    public function testTagDetailsUpdate()
    {
    	$tag = factory(Tag::class)->make();
        $tag->save();

    	$response = $this->json('POST', '/api/tags/update/term', [
            'title' => str_random(10)
        ]);

        $response->assertStatus(200);
    }

    public function testViewAllCategory()
    {
    	$category = factory(Category::class)->make();
        $category->save();

        $response = $this->json('GET', '/api/library/categories');

        $response->assertStatus(200);
    }

    public function testFilterAllCategoryWithPagination()
    {
    	$response = $this->json('GET', '/api/filter/categories?goto=2');

        $response->assertStatus(200);
    }

    public function testFilterAllCategoryWithSearch()
    {
    	$response = $this->json('GET', '/api/filter/categories?search_term=qui');

        $response->assertStatus(200);
    }

    public function testSelectedCategoryPreview()
    {
    	$category = factory(Category::class)->make();
        $category->save();

    	$response = $this->json('POST', '/api/filter/categories', [
            'category_id' => $category->id
        ]);

        $response->assertStatus(200);
    }

    public function testCategoryDetailsUpdate()
    {
    	$category = factory(Category::class)->make();
        $category->save();

    	$response = $this->json('POST', '/api/categories/update', [
            'title' => str_random(10)
        ]);

        $response->assertStatus(200);
    }

    public function testViewAllVideo()
    {
    	$category = factory(Category::class)->make();
        $category->save();

        $tag = factory(Tag::class)->make();
        $tag->save();

        $actor = factory(Actor::class)->make();
        $actor->save();

    	$video = factory(Video::class)->make();
        $video->save();

        VideoActor::create(['video_id' => $video->id, 'actor_id' => $actor->id]);
        VideoTag::create(['video_id' => $video->id, 'tag_id' => $tag->id]);
        VideoCategory::create(['video_id' => $video->id, 'category_id' => $category->id]);

    	$response = $this->json('GET', '/api/library/videos');

        $response->assertStatus(200);
    }

    public function testFilterVideoWithPagination()
    {
    	$response = $this->json('GET', '/api/filter/videos?goto=2');

        $response->assertStatus(200);
    }

    public function testSelectedVideoPreview()
    {
    	$category = factory(Category::class)->make();
        $category->save();

        $tag = factory(Tag::class)->make();
        $tag->save();

        $actor = factory(Actor::class)->make();
        $actor->save();

    	$video = factory(Video::class)->make();
        $video->save();

        VideoActor::create(['video_id' => $video->id, 'actor_id' => $actor->id]);
        VideoTag::create(['video_id' => $video->id, 'tag_id' => $tag->id]);
        VideoCategory::create(['video_id' => $video->id, 'category_id' => $category->id]);

    	$response = $this->json('POST', '/api/filter/videos', [
            'video_id' => $video->id
        ]);

        $response->assertStatus(200);
    }
}
