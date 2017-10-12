<?php

/**
 * Created by PhpStorm.
 * User: yz
 * Date: 17/10/8
 * Time: 下午4:31
 */


Route::group(['middleware' => 'token', 'prefix' => '/vote'], function () {
    Route::post('/create', 'ProjectController@create');

});
Route::group(['prefix' => '/vote'], function () {
    Route::get('/list', 'ProjectController@getProjectList');
    Route::get('/detail/{projectId}','ProjectController@getProjectDetail');
    Route::post('/pic','PicController@uploadPic');
});
