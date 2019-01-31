<?php

namespace App\Http\Controllers\Functions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaginationController extends Controller
{
    public static function pagination(Array $currentArray)
    {
    	$pageSize = $currentArray['size'];
    	$total_size = $currentArray['total'];
        $nextPage = $currentArray['goto'];
        $refresh = $currentArray['refresh'];
        $firstPage = $currentArray['first'];
        $lastPage = $currentArray['last'];

        if($lastPage){
            $temp_size = $total_size;
            $numb_pages = 0;
            while($temp_size > $pageSize){
                $numb_pages++;
                $temp_size -= $pageSize;
            }
            if($temp_size > 0){
                $numb_pages++;
            }
            $nextPage = $numb_pages;
        }

        if($firstPage){
            $nextPage = 1;
        }

        if($refresh){
            $nextPage = 1;
        }
        
        $start = (($nextPage - 1) * $pageSize);

        //check if there is a next page
        if(($nextPage * $pageSize)>=$total_size){
            $next = false;
        }
        else
        {
            $next = true;
        }

        if($nextPage > 1){
            $back = true;
        }
        else
        {
            $back = false;
        }


        $info = [
        	'next'=>$next,
        	'back'=>$back,
        	'start'=>$start,
        	'nextPage'=>$nextPage,
        	'pageSize'=>$pageSize
        		];

        return $info;
    }
}
