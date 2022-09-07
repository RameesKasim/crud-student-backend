<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController; 
use App\Http\Controllers\StudentController; 



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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



Route::post( '/register', [ AuthController::class, 'userRegister' ] );
Route::post( '/login', [ AuthController::class, 'userLogin' ] );


Route::group(['middleware' => ['auth:sanctum']], function () {
    //logutroute
    Route::post('/logout', [AuthController::class, 'logout']);

    //geeting course details
    Route::post('/addcourse', [CourseController::class, 'store']);
    Route::get('/getcourse', [CourseController::class, 'index']);
    Route::get('/getcourse/{id}', [CourseController::class, 'show']);

    // student api url
    Route::post('/student', [StudentController::class, 'store']);
    Route::put('/editstudent/{id}', [StudentController::class, 'update']);
    Route::get('/studentlist', [StudentController::class, 'index']);
    Route::get('/student/{id}', [StudentController::class, 'show']);
    Route::delete('student/{id}',[StudentController::class, 'destroy']);

});
