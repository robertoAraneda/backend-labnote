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
  Route::apiResource('states', 'StateController')->names('api.states');
  Route::apiResource('work-areas', 'WorkAreaController')->names('api.workAreas');
  Route::apiResource('sections', 'SectionController')->names('api.sections');
  Route::apiResource('vih-keys', 'VihKeyController')->names('api.vihKeys');

  Route::group([
    'prefix' => 'states',
    'middleware' => 'auth:api'
  ], function () {

    Route::get('{state}/sections', 'StateController@sections')->name('api.states.sections');
    Route::get('{state}/work-areas', 'StateController@workAreas')->name('api.states.workAreas');
    Route::get('{state}/vih-keys', 'StateController@vihKeys')->name('api.states.vihKeys');
  });

  Route::group([
    'prefix' => 'sections',
    'middleware' => 'auth:api'
  ], function () {
    Route::get('{section}/work-areas', 'SectionController@workAreas')->name('api.sections.workAreas');
  });
});


// Route::post('/login', 'AuthController@login');

// Route::middleware('auth:api')->post('/logout', 'AuthController@logout');
