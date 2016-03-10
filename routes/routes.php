<?php

Route::get('/join/{recruitment_key}', ['as' => 'recruitment.join', 'uses' => '\Klsandbox\RecruitmentRoute\Http\Controllers\RecruitmentManagementController@getJoin']);
Route::post('/join/phone', ['as' => 'recruitment.join', 'uses' => '\Klsandbox\RecruitmentRoute\Http\Controllers\RecruitmentManagementController@postPhone']);

Route::group(['middleware' => ['auth']], function () {

    Route::controllers([
        'recruitment-management' => '\Klsandbox\RecruitmentRoute\Http\Controllers\RecruitmentManagementController',
    ]);

    Route::group(['middleware' => ['auth.admin']], function () {
        Route::get('leaderboard/top-recruitment-users/{filter}', '\Klsandbox\RecruitmentRoute\Http\Controllers\RecruitmentManagementController@getTopRecruitmentUsers');
    });
});
