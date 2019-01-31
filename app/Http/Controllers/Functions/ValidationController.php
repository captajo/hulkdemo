<?php

namespace App\Http\Controllers\Functions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;

class ValidationController extends Controller
{
    public static function validateRule($rules, $input)
    {
        //input validation parameters
    	$validator = Validator::make($input, $rules);

        //check if any parameter fails, if so, route back
        if ($validator->fails()) {
            exit(\GuzzleHttp\json_encode([
                'status'=>'fail',
                'data'=>'Validation Error '.$validator->errors()->all()[0],
            ]));
        }
    }
}
