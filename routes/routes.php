<?php

Route::get('/join/{recruitment_key}', ['as' => 'recruitment.join', 'uses' => '\Klsandbox\RecruitmentRoute\Http\Controllers\RecruitmentManagementController@getJoin']);
Route::post('/join/phone', ['as' => 'recruitment.join', 'uses' => '\Klsandbox\RecruitmentRoute\Http\Controllers\RecruitmentManagementController@postPhone']);

Route::group(['middleware' => ['auth']], function () {

    Route::group(['middleware' => ['role:stockist']], function () {
        Route::post('/recruitment-management/settings', ['uses' => '\Klsandbox\RecruitmentRoute\Http\Controllers\RecruitmentManagementController@postSettings']);
        Route::get('/recruitment-management/settings', ['uses' => '\Klsandbox\RecruitmentRoute\Http\Controllers\RecruitmentManagementController@getSettings']);
        Route::get('/recruitment-management/list-recruitments/{user}', ['uses' => '\Klsandbox\RecruitmentRoute\Http\Controllers\RecruitmentManagementController@getListRecruitments']);
    });

    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('leaderboard/top-recruitment-users/{filter}', '\Klsandbox\RecruitmentRoute\Http\Controllers\RecruitmentManagementController@getTopRecruitmentUsers');
    });
});