<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Video;

use App\Http\Controllers\Functions\SearchController;

class testController extends Controller
{
    public function test()
    {
    	// $all_videos = Video::with(['actors', 'tags', 'categories'])->get();

    	// foreach($all_videos as $vid) {
    	// 	echo $vid->title.' - '.$vid->description.'<br/>';
    	// 	$actors = $vid->actors;
    	// 	$listed_actors = $actors->pluck('title')->toArray();
    	// 	$tags = $vid->tags;
    	// 	$listed_tags = $tags->pluck('title')->toArray();
    	// 	echo '['.implode(', ', $listed_actors).'] <br/>';
    	// 	echo '['.implode(', ', $listed_tags).'] <br/>';
    	// 	echo '<br/><br/><br/>';
    	// }
    	// print_r($all_videos);

    	$search = new SearchController();
    	// if(!$search->deleteAllIndex()) {
    	if(!$search->indexElasticSearch()) {
    		echo 'Index failed';
    		exit;
    	}
    	exit('completed');
    }

    public function search($search_term)
    {
    	$search = new SearchController();
    	$result = $search->searchDirectory($search_term, 1);
    	dump($result);
    }
}
