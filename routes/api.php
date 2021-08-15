<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StudentController;
use App\Models\Department;
use App\Models\Result;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

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

Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});
Route::get('department-list', function () {
    return Department::list();
});
Route::get('student-list', function () {
    return Student::list();
});
Route::get('result-gpa-list', function () {
    return Result::gpaList();
});
Route::resource('departments', DepartmentController::class);
Route::resource('students', StudentController::class);
Route::resource('results', ResultController::class);
Route::resource('users', UserController::class);
Route::get('dashboard', DashboardController::class);
