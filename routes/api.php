<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
  return $request->user();
});

Route::group([
  'prefix' => 'auth',
], function () {
  Route::post('login', 'AuthController@login');
  Route::post('signup', 'AuthController@signup');
});

Route::group([
  'prefix' => 'v2',
  'middleware' => 'auth:api'
], function () {
  Route::get('logout', 'AuthController@logout');
  Route::get('user', 'AuthController@user');
  Route::apiResource('states', 'StateController');
  Route::apiResource('workareas', 'WorkareaController');
  Route::apiResource('sections', 'SectionController');
  Route::group([
    'prefix' => 'states',
    'middleware' => 'auth:api'
  ], function () {
    Route::get('{idState}/sections', 'StateController@sections');
    Route::get('{idState}/workareas', 'StateController@workareas');
  });
});


// Route::post('/login', 'AuthController@login');

// Route::middleware('auth:api')->post('/logout', 'AuthController@logout');
