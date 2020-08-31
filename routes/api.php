<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::group(['middleware' => 'auth:sanctum'], function () {
Route::middleware('auth:sanctum', 'throttle:60,1')->group( function () {
    Route::post('/logout', 'Api\AuthController@logout');
    Route::get('/user', 'Api\AuthController@user_login');
    Route::get('/user_list_params', 'Api\AuthController@user_list_params');
    Route::put('/user_status/{id}/edit', 'Api\AuthController@user_status');
    Route::get('/user_by/{id}/edit', 'Api\AuthController@user_by');
    Route::delete('/user_remove/{id}', 'Api\AuthController@user_remove');
    Route::post('/remove_selected', 'Api\AuthController@remove_selected');
    Route::put('/upload_image/{user}', 'Api\AuthController@upload_image');
    Route::put('/user-update/{user}', 'Api\AuthController@update_data');
    Route::get('/users-export', 'UsersExportController@export');
    Route::post('/users-import', 'Api\AuthController@import');
});

// Route::post('/login', function (Request $request) {

//     $request->validate([
//         'email'=>'required|email',
//         'password'=>'required'
//     ]);

//     $auth = $request->except(['remember_me']);
//     if (auth()->attempt($auth, $request->remember_me)) {

//         return response()->json(['status' => 'success', 'data' => auth()->user()], 200);
//     }

//     return response()->json(['status' => 'failed']);

//     // auth()->attempt($request->only('email', 'password'));
//     // return auth()->user();    
// });
// Route::post('logout', function () {

//     auth()->logout();
//     return response('');    
// });
Route::post('/login', 'Api\AuthController@login');
// Route::post('/logout', 'Api\AuthController@logout')->middleware('auth:sanctum');
Route::post('/register', 'RegisterController@store');