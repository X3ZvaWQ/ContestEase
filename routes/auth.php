<?php

use Illuminate\Http\Request;


Route::group(['namespace' => 'Api'], function () {
    Route::post('token', 'AuthController@login');
});