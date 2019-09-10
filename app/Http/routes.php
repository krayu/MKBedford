<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use Illuminate\Http\Request;
Route::group(['middleware' => ['web']], function () {

	//main page
    Route::get('/', 'AdController@index');

    //show more ads
    Route::post('/show-more', 'AdController@showMore'); 
    
    //show category
    Route::get('/show/{specific}', 'AdController@show');
    
    //only one ad
    Route::get('/item/{id}/{string}', 'AdController@item');
    
    //send search query
    Route::post('/search', function(Request $request){
    	if(strlen($request->searched) > 2)
    		return redirect('/results/'.$request->searched);
    	else
    		return redirect('/');
    });

    //show search results
    Route::get('/results/{val}', 'AdController@results');

    //adding ads
    Route::get('/addads', 'AdController@addAds');
   

});
