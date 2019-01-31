<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Functions\SearchController;
use App\Http\Controllers\Functions\PaginationController;
use App\Http\Controllers\Functions\ValidationController;

use Carbon\Carbon;
use Response;
use Cache;

class SearchItemController extends Controller
{
    public function search(Request $request)
    {
        //addition of input validation
        ValidationController::validateRule(['search_term' => 'string', 'goto' => 'integer', 'first' => 'string|max:7', 'last' => 'string|max:7', 'total' => 'integer' ], $request->all());

    	//search variable
    	$search_term = $request->get('search_term');

    	//pagination variables
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

    	//check if an item exist to search
    	if(!$search_term)
    		return Response::json(['status' => 'success', 'data' => [ 'result' => []]], 200);

    	//set default result to null
    	$result = [];

    	//remove white spaces to allow check/store in memcache
    	$memcache_term = str_slug($search_term.$goto,'-');

    	//check if item is cached in memcache before
    	if(Cache::has($memcache_term)) {
    		// $result = Cache::get($memcache_term);
    	}

    	//if no entry from application cache
    	if(!count($result)) {
    		//search for selected item
	    	$search = new SearchController();
	    	$result = $search->searchDirectory($search_term, $pageStructure['start']);
	    	
	    	//set expiry date for memcache
	    	$expiresAt = Carbon::now()->addMinutes(10);
	    	Cache::put($memcache_term, $result, $expiresAt);
    	}

    	$next = true;
    	//check if there is a next page
        if(($pageStructure['nextPage'] * $size)>=$result['page_data']['total_result']){
            $next = false;
        }

    	//report page structure
    	$info = [
            'next'=>$next,
            'back'=>$pageStructure['back'],
            'nextPage'=>$pageStructure['nextPage']
                ];

    	return Response::json(['status' => 'success', 'data' => $result, 'pager' => $info], 200);
    }
}
