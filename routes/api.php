<?php

use Illuminate\Http\Request;

Route::get('/getbranch', 'AdminController@GetBranch');
Route::post('/createbranch', 'AdminController@CreateBranch');

Route::get('/getemplo', 'AdminController@GetEmplo');
Route::post('/createemplo', 'AdminController@CreateEmplo');

Route::get('/getemployeedetails/{key}', 'AdminController@GetEmployeeDetails');
Route::get('/getemployeelogs/{id}', 'AdminController@GetEmployeeLogs');
Route::post('/employeetimein', 'AdminController@EmployeeTimein');
Route::post('/employeetimeout', 'AdminController@EmployeeTimeout');
