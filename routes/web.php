<?php

Route::get('/', function () {
    return view('index');
});

Route::group(['prefix' => 'admin'], function(){
    Route::get('/dashboard', function () {
        return view('index');
    });
    Route::get('/branch', function () {
        return view('index');
    });
    Route::get('/employee', function () {
        return view('index');
    });

});

Route::get('/employee-logs', function () {
    return view('index');
});
