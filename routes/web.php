<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::any('/password/{any}', function ($any) {
    if(request()->method() == 'GET') {
        return redirect('/');
    }else{
        return response()->json([
            'ret'  => 403,
            'desc' => 'forbidden!',
            'data' => ''
        ], 403);
    }
});

Auth::routes(['register' => false]);


