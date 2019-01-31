<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Functions\SearchController;
use App\Model\Video;

use Response;
use Cache;

class ReIndexController extends Controller
{
    public function reindex()
    {
    	//re-index the library
    	$search = new SearchController();
	    if(!$search->reIndexLibrary()) {
	    	Response::json(['status' => 'fail', 'data' => 'An error occured']);
	    }	

	    //clear all memcache data
    	Cache::flush();

    	return Response::json(['status' => 'success', 'data' => 'Indexing Complete']);
    }

    public function indexLatest()
    {
        //count total records in index
        $search = new SearchController();
        $total = $search->countIndexRecord();

        //get latest videos from 
        $latest_videos = $this->getLatestVideo($total);
        $size = count($latest_videos);

        //if no new video
        if(!$size)
            return Response::json(['status' => 'success', 'data' => 'Already up-to-date. No new videos to index']);

        //if an error occured indexing new video
        if(!$search->indexElasticSearch($latest_videos)) 
            return Response::json(['status' => 'fail', 'data' => 'An error occured indexing '.$size.' new videos']);

        return Response::json(['status' => 'success', 'data' => $size.' new videos added to index']);
    }

    public function getLatestVideo($from)
    {
        return Video::where('id', '>', $from)->with(['actors', 'tags', 'categories'])->get();
    }
}
