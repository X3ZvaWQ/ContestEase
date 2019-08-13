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

Route::group(['middleware' => 'auth:api', 'namespace' => 'Api'], function () {
    Route::get('user', 'UserController@info');
    Route::get('status', 'ContestController@status');
    Route::get('notice', 'NoticeController@fetch');
    Route::get('questions', 'ProblemController@list');
    Route::get('submitted', 'ContestController@submitted');
    Route::post('submit', 'ContestController@submit');

    Route::group(['prefix' => 'admin','middleware' => 'contest.admin'], function () {
        Route::post('modifyNotice', 'NoticeController@modify')->middleware('after.updateNoticesMD5');
        Route::post('modifyDue', 'ContestController@modify');
        Route::post('modifyQuestions', 'ProblemController@modify')->middleware('after.updateProblemsMD5');;
        Route::post('addSource', 'ProblemController@addSource')->middleware('after.updateProblemsMD5');;
        Route::post('deleteSource', 'ProblemController@deleteSource')->middleware('after.updateProblemsMD5');;
        Route::post('modifyQuestionsMassively', 'ProblemController@modifyMassively')->middleware('after.updateProblemsMD5');;
    });
});


