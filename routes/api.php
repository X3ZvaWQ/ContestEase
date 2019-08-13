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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api', 'namespace' => 'Api'], function () {
    Route::get('status', 'ContestController@status');
    Route::get('notice', 'NoticeController@fetch');
    Route::get('questions', 'ProblemController@list');
    Route::get('submitted', 'ContestController@submitted');
    Route::post('submit', 'ContestController@submit');

    Route::group(['prefix' => 'admin','middleware' => 'contest.admin'], function () {
        Route::post('modifyNotice', 'NoticeController@modify');
        Route::post('modifyDue', 'ContestController@modify');
        Route::post('modifyQuestions', 'ProblemController@modify');
        Route::post('addSource', 'ProblemController@addSource');
        Route::delete('deleteSource', 'ProblemController@deleteSource');
        Route::post('modifyQuestionsMassively', 'ProblemController@modifyMassively');
    });
});


