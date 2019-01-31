<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

//item search
Route::post('/search', 'General\SearchItemController@search');

//elastic search indexing
Route::delete('/library/re-index', 'General\ReIndexController@reindex');
Route::put('/library/index/latest', 'General\ReIndexController@indexLatest');

//update videos records
Route::get('/library/videos', 'General\RecordUpdateController@allVideo');
Route::get('/filter/videos', 'General\RecordUpdateController@filterVideo');
Route::post('/filter/videos', 'General\RecordUpdateController@previewVideo');
Route::post('/videos/update', 'General\RecordUpdateController@updateVideo');

//update actor records
Route::get('/library/actors', 'General\RecordUpdateController@allActors');
Route::get('/filter/actors', 'General\RecordUpdateController@filterActors');
Route::post('/filter/actors', 'General\RecordUpdateController@previewActor');
Route::post('/actors/update', 'General\RecordUpdateController@updateActor');

//update tag records
Route::get('/library/tags', 'General\RecordUpdateController@allTags');
Route::get('/filter/tags', 'General\RecordUpdateController@filterTags');
Route::post('/filter/tags', 'General\RecordUpdateController@previewTag');
Route::post('/tags/update/term', 'General\RecordUpdateController@updateTag');

//update categories records
Route::get('/library/categories', 'General\RecordUpdateController@allCategories');
Route::get('/filter/categories', 'General\RecordUpdateController@filterCategory');
Route::post('/filter/categories', 'General\RecordUpdateController@previewCategory');
Route::post('/categories/update', 'General\RecordUpdateController@updateCategory');