<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Functions\SearchController;
use App\Http\Controllers\Functions\PaginationController;

use App\Model\Video;
use App\Model\Actor;
use App\Model\Category;
use App\Model\Tag;
use App\Model\VideoActor;
use App\Model\VideoTag;
use App\Model\VideoCategory;
use Response;

class RecordUpdateController extends Controller
{
    //video updates
    public function allVideo()
    {
    	$all_videos = Video::take(10)->get();
    	$total = Video::count();
    	return Response::json(['status' => 'success', 'data' => $all_videos, 'total' => $total], 200);
    }

    public function filterVideo(Request $request)
    {
    	$search_term = $request->get('search_term');
    	$goto = ($request->get('goto'))? $request->get('goto') : 1;
    	$first = (bool)($request->get('first') == 'true')? $request->get('first') : false;
    	$last = (bool)($request->get('last') == 'true')? $request->get('last') : false;
    	$total = ($request->get('total'))? $request->get('total') : 0;
    	$size = 10;

    	//generate current page structure
    	$pageStructure = PaginationController::pagination([
    		'size' => $size,
    		'total' => $total,
    		'last' => $last,
    		'first' => $first,
    		'refresh' => false,
    		'goto' => $goto
    	]);

    	$all_videos = Video::skip($pageStructure['start'])->take($pageStructure['pageSize'])->get();

    	//report page structure
    	$info = [
    		'info'=>$all_videos,
            'next'=>$pageStructure['next'],
            'back'=>$pageStructure['back'],
            'nextPage'=>$pageStructure['nextPage']
                ];

        return Response::json(['status' => 'success', 'data' => $info]);
    }	

    public function previewVideo(Request $request)
    {
    	$video_id = $request->get('video_id');

    	if(!$video = $this->getVideo($video_id))
    		return Response::json(['status' => 'fail', 'data' => 'Can\'t find video']);

    	return Response::json(['status' => 'success', 'data' => $video], 200);
    }

    public function updateVideo(Request $request)
    {
    	$request = $request->json();
    	$video_id = $request->get('video_id');
    	// return Response::json($video_id);
    	
    	//find video
    	if(!$video = $this->getVideo($video_id))
    		return Response::json(['status' => 'fail', 'data' => 'Can\'t find video']);

    	if(!$this->updateSelectedVideo($video, $request)) 
    		return Response::json(['status' => 'fail', 'data' => 'Can\'t find video']);

    	return Response::json(['status' => 'success', 'data' => 'Video Updated']);
    }

    //actor updates
    public function allActors()
    {
    	$all_actors = Actor::take(10)->get();
    	$total = Actor::count();
    	return Response::json(['status' => 'success', 'data' => $all_actors, 'total' => $total]);
    }

    public function filterActors(Request $request)
    {
    	$search_term = $request->get('search_term');
    	$goto = ($request->get('goto'))? $request->get('goto') : 1;
    	$first = (bool)($request->get('first') == 'true')? $request->get('first') : false;
    	$last = (bool)($request->get('last') == 'true')? $request->get('last') : false;
    	$total = ($request->get('total'))? $request->get('total') : 0;
    	$size = 10;

    	//generate current page structure
    	$pageStructure = PaginationController::pagination([
    		'size' => $size,
    		'total' => $total,
    		'last' => $last,
    		'first' => $first,
    		'refresh' => false,
    		'goto' => $goto
    	]);

    	if($search_term)
    		$search_term = '%'.$search_term.'%';

    	if($search_term) {
    		$all_actors = Actor::where('title', 'like', $search_term)->skip($pageStructure['start'])->take($pageStructure['pageSize'])->get();
    	} else {
    		$all_actors = Actor::skip($pageStructure['start'])->take($pageStructure['pageSize'])->get();
    	}
    	

    	//report page structure
    	$info = [
    		'info'=>$all_actors,
            'next'=>$pageStructure['next'],
            'back'=>$pageStructure['back'],
            'nextPage'=>$pageStructure['nextPage']
                ];

        return Response::json(['status' => 'success', 'data' => $info]);
    }	

    public function previewActor(Request $request)
    {
    	$actor_id = $request->get('actor_id');
    	
    	if(!$actor = $this->getActor($actor_id))
    		return Response::json(['status' => 'fail', 'data' => 'Can\'t find video']);

    	return Response::json(['status' => 'success', 'data' => $actor], 200);
    }

    public function updateActor(Request $request)
    {

    	$actor_id = $request->get('actor_id');
    	
    	if(!$actor = $this->getActor($actor_id))
    		return Response::json(['status' => 'fail', 'data' => 'Can\'t find actor']);

    	if(!$this->updateSelectedVideo($actor, $request)) 
    		return Response::json(['status' => 'fail', 'data' => 'Error updating actor']);

    	return Response::json(['status' => 'success', 'data' => 'Actor Details Updated']);
    }

    //tag updates
    public function allTags()
    {
    	$all_tags = Tag::take(10)->get();
    	$total = Tag::count();
    	return Response::json(['status' => 'success', 'data' => $all_tags, 'total' => $total]);
    }

    public function filterTags(Request $request)
    {
    	$search_term = $request->get('search_term');
    	$goto = ($request->get('goto'))? $request->get('goto') : 1;
    	$first = (bool)($request->get('first') == 'true')? $request->get('first') : false;
    	$last = (bool)($request->get('last') == 'true')? $request->get('last') : false;
    	$total = ($request->get('total'))? $request->get('total') : 0;
    	$size = 10;

    	//generate current page structure
    	$pageStructure = PaginationController::pagination([
    		'size' => $size,
    		'total' => $total,
    		'last' => $last,
    		'first' => $first,
    		'refresh' => false,
    		'goto' => $goto
    	]);

    	if($search_term)
    		$search_term = '%'.$search_term.'%';

    	if($search_term) {
    		$all_tags = Tag::where('title', 'like', $search_term)->skip($pageStructure['start'])->take($pageStructure['pageSize'])->get();
    	} else {
    		$all_tags = Tag::skip($pageStructure['start'])->take($pageStructure['pageSize'])->get();
    	}

    	//report page structure
    	$info = [
    		'info'=>$all_tags,
            'next'=>$pageStructure['next'],
            'back'=>$pageStructure['back'],
            'nextPage'=>$pageStructure['nextPage']
                ];

        return Response::json(['status' => 'success', 'data' => $info]);
    }	

    public function previewTag(Request $request)
    {
    	$tag_id = $request->get('tag_id');
    	
    	if(!$tag = $this->getTag($tag_id))
    		return Response::json(['status' => 'fail', 'data' => 'Can\'t find tag']);

    	return Response::json(['status' => 'success', 'data' => $tag], 200);
    }

    public function updateTag(Request $request)
    {
    	$tag_id = $request->get('tag_id');
    	
    	if(!$tag = $this->getTag($tag_id))
    		return Response::json(['status' => 'fail', 'data' => 'Can\'t find tag']);

    	// return Response::json($tag->videos);

    	if(!$this->updateSelectedTag($tag, $request)) 
    		return Response::json(['status' => 'fail', 'data' => 'Error updating tag']);

    	return Response::json(['status' => 'success', 'data' => 'Tag Details Updated']);
    }

    //category updates
    public function allCategories()
    {
    	$all_categories = Category::take(10)->get();
    	$total = Category::count();
    	return Response::json(['status' => 'success', 'data' => $all_categories, 'total' => $total]);
    }

    public function filterCategory(Request $request)
    {
    	$search_term = $request->get('search_term');
    	$goto = ($request->get('goto'))? $request->get('goto') : 1;
    	$first = (bool)($request->get('first') == 'true')? $request->get('first') : false;
    	$last = (bool)($request->get('last') == 'true')? $request->get('last') : false;
    	$total = ($request->get('total'))? $request->get('total') : 0;
    	$size = 10;

    	//generate current page structure
    	$pageStructure = PaginationController::pagination([
    		'size' => $size,
    		'total' => $total,
    		'last' => $last,
    		'first' => $first,
    		'refresh' => false,
    		'goto' => $goto
    	]);

    	if($search_term)
    		$search_term = '%'.$search_term.'%';

    	if($search_term) {
    		$all_categories = Category::where('title', 'like', $search_term)->skip($pageStructure['start'])->take($pageStructure['pageSize'])->get();
    	} else {
    		$all_categories = Category::skip($pageStructure['start'])->take($pageStructure['pageSize'])->get();
    	}

    	//report page structure
    	$info = [
    		'info'=>$all_categories,
            'next'=>$pageStructure['next'],
            'back'=>$pageStructure['back'],
            'nextPage'=>$pageStructure['nextPage']
                ];

        return Response::json(['status' => 'success', 'data' => $info]);
    }	

    public function previewCategory(Request $request)
    {
    	$category_id = $request->get('category_id');
    	
    	if(!$category = $this->getCategory($category_id))
    		return Response::json(['status' => 'fail', 'data' => 'Can\'t find category']);

    	return Response::json(['status' => 'success', 'data' => $category], 200);
    }

    public function updateCategory(Request $request)
    {
    	$category_id = $request->get('category_id');
    	
    	if(!$category = $this->getCategory($category_id))
    		return Response::json(['status' => 'fail', 'data' => 'Can\'t find category']);

    	if(!$this->updateSelectedCategory($category, $request)) 
    		return Response::json(['status' => 'fail', 'data' => 'Error updating category']);

    	return Response::json(['status' => 'success', 'data' => 'Category Details Updated']);
    }

    private function updateSelectedCategory($category, $request)
	{
		$title = $request->get('title');

		\DB::beginTransaction();
		try {
			$category->title = $title;
			$category->save();

			$videos = $category->videos->pluck('video_id')->toArray();

			if(count($videos)) {
				$videos = Video::whereIn('id', $videos)->with(['actors', 'tags', 'categories'])->get();
		    	$search = new SearchController();
		    	if(!$search->updateEntries($videos)) {
		    		return false;
		    	}
		    }
		} catch (\Exception $ex) {
			\DB::rollBack();
			return false;
		}
		\DB::commit();
		return true;
	}    

    private function getCategory($tag_id)
    {
    	return Category::where('id', $tag_id)->with('videos')->first();
    }

    private function updateSelectedTag($tag, $request)
	{
		$title = $request->get('title');

		\DB::beginTransaction();
		try {
			$tag->title = $title;
			$tag->save();

			$videos = $tag->videos->pluck('video_id')->toArray();

			if(count($videos)) {
				$videos = Video::whereIn('id', $videos)->with(['actors', 'tags', 'categories'])->get();
		    	$search = new SearchController();
		    	if(!$search->updateEntries($videos)) {
		    		return false;
		    	}
			}
				
		} catch (Exception $ex) {
			\DB::rollBack();
			return false;
		}
		\DB::commit();
		return true;
	}    

    private function getTag($tag_id)
    {
    	return Tag::where('id', $tag_id)->with('videos')->first();
    }

	private function updateSelectedActor($actor, $request)
	{
		$title = $request->get('title');

		\DB::beginTransaction();
		try {
			$actor->title = $title;
			$actor->save();

			$videos = $actor->videos->pluck('video_id')->toArray();

			if(count($videos)) {
				$videos = Video::whereIn('id', $videos)->with(['actors', 'tags', 'categories'])->get();
		    	$search = new SearchController();
		    	if(!$search->updateEntries($videos)) {
		    		return false;
		    	}
		    }
		} catch (\Exception $ex) {
			\DB::rollBack();
			return false;
		}
		\DB::commit();
		return true;
	}    

    private function getActor($actor_id)
    {
    	return Actor::where('id', $actor_id)->with('videos')->first();
    }

    private function updateSelectedVideo($video, $request)
    {
    	$title = $request->get('title');
    	$description = $request->get('description');
    	$actors = $request->get('actors');
    	$tags = $request->get('tags');
    	$categories = $request->get('categories');

    	\DB::beginTransaction();
    	try {
    		//update video
    		$video->title = $title;
	    	$video->description = $description;
	    	$video->save();

	    	//update video actor
	    	if(!$this->updateVideoActor($video, $actors)) {
	    		return false;
	    	}

	    	//update video category
	    	if(!$this->updateVideoCategory($video, $categories)) {
	    		return false;
	    	}

	    	//update video tag
	    	if(!$this->updateVideoTag($video, $tags)) {
	    		return false;
	    	}

	    	//update video in elasticsearch index
	    	$videos = Video::where('id', $video->id)->with(['actors', 'tags', 'categories'])->get();
	    	$search = new SearchController();
	    	if(!$search->updateEntries($videos)) {
	    		return false;
	    	}

    	} catch(\Exception $ex) {
    		\DB::rollBack();
    		return false;
    	}
    	\DB::commit();
    	return true;
    }

    private function updateVideoCategory($video, $categories)
    {
    	try {
    		$all_categories = $video->categories;
	    	$selected_categories = $all_categories->pluck('id')->toArray();
	    	$category_diff = $this->diff($categories, $selected_categories);

	    	if(count($category_diff['remove']) > 0) {
	    		if(!VideoCategory::where('video_id', $video->id)->whereIn('category_id', $category_diff['remove'])->delete()) {
	    			return false;
	    		}
	    	}

	    	$video_category_insert = [];
	    	foreach($category_diff['add'] as $category_id) {
	    		$video_category_insert[] = [
	    			'video_id' => $video->id,
	    			'category_id' => $category_id,
	    		];
	    	}

	    	if(count($video_category_insert) > 0)
	    		VideoCategory::insert($video_category_insert);

    	} catch(\Exception $ex) {
    		return false;
    	}
    	return true;
    }

    private function updateVideoTag($video, $tags)
    {
    	try {
    		$all_tags = $video->tags;
	    	$selected_tags = $all_tags->pluck('id')->toArray();
	    	$tag_diff = $this->diff($tags, $selected_tags);

	    	if(count($tag_diff['remove']) > 0) {
	    		if(!VideoTag::where('video_id', $video->id)->whereIn('tag_id', $tag_diff['remove'])->delete()) {
	    			return false;
	    		}
	    	}

	    	$video_tag_insert = [];
	    	foreach($tag_diff['add'] as $tag_id) {
	    		$video_tag_insert[] = [
	    			'video_id' => $video->id,
	    			'tag_id' => $tag_id,
	    		];
	    	}

	    	if(count($video_tag_insert) > 0)
	    		VideoTag::insert($video_tag_insert);

    	} catch(\Exception $ex) {
    		return false;
    	}
    	return true;
    }

    private function updateVideoActor($video, $actors)
    {
    	try {
    		$all_actors = $video->actors;
	    	$selected_actors = $all_actors->pluck('id')->toArray();
	    	$actor_diff = $this->diff($actors, $selected_actors);

	    	if(count($actor_diff['remove']) > 0) {
	    		if(!VideoActor::where('video_id', $video->id)->whereIn('actor_id', $actor_diff['remove'])->delete()) {
	    			return false;
	    		}
	    	}

	    	$video_actor_insert = [];
	    	foreach($actor_diff['add'] as $actor_id) {
	    		$video_actor_insert[] = [
	    			'video_id' => $video->id,
	    			'actor_id' => $actor_id,
	    		];
	    	}

	    	if(count($video_actor_insert) > 0)
	    		VideoActor::insert($video_actor_insert);

    	} catch(\Exception $ex) {
    		return false;
    	}
    	return true;
    }

    private function diff($new, $old)
    {
    	$add = array_diff($new, $old);
    	$remove = array_diff($old, $new);
    	return ['add' => $add, 'remove' => $remove];
    }

    private function getVideo($video_id)
    {
    	return Video::where('id', $video_id)->with(['actors', 'tags', 'categories'])->first();
    }
}
