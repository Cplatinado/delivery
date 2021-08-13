<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\aclController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\betaController;
use App\Http\Controllers\api\CourseController;
use App\Http\Controllers\api\userController;
use App\Http\Controllers\api\userControoler;
use App\Http\Controllers\api\trakingController;
use App\Http\Controllers\api\notionsController;
use App\Http\Controllers\api\favoriteController;
use App\Http\Controllers\highlightControoler;

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

Route::post('login', [AuthController::class, 'login']);
Route::post('confirmLogin', [AuthController::class, 'confirmLogin']);
Route::post('/send/recover', [userController::class, 'sendRecoverPassword']);
Route::post('/confirm/email', [AuthController::class, 'confirmEmail']);
Route::put('recover/password/{token}',[userController::class, 'recoverPassword']);
Route::post('/store/user', [userController::class, 'storeUsers']);
Route::post('/resend/confirm', [userController::class, 'resendConfirm']);
Route::put('/complete/user', [userController::class, 'completeRegister']);




Route::group(['middleware' => ['Jwt']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);


    // ACL
    Route::put('/store/role', [aclController::class, 'storeRole']);
    Route::put('/store/permission', [aclController::class, 'storePermission']);

    Route::get('/get/roles', [aclController::class, 'getRoles']);
    Route::get('/get/permissions', [aclController::class, 'getPermissions']);
    Route::post('/permissions/sync', [aclController::class, 'permissionSync']);

    Route::get('/users', [userController::class, 'getUsers']);
    Route::post('/sync/roles', [aclController::class, 'rolesSync']);

    Route::get('/adms', [aclController::class, 'getAdmins']);


    // user
    Route::post('/store/adm', [userController::class, 'storeAdm']);
    Route::post('/store/producer', [userController::class, 'storeProducer']);
    Route::post('/store/suport', [userController::class, 'storeSuport']);
    Route::post('/update/profile', [userController::class, 'updateProfile']);
    Route::put('/new/password', [userController::class, 'newPassord']);


    // Course
    Route::put('/store/course', [CourseController::class, 'storeCourse']);
    Route::post('/store/module', [CourseController::class, 'storeModule']);
    Route::get('/get/course/{course}', [CourseController::class, 'getCouse']);
    Route::post('/store/class', [CourseController::class, 'storeClass']);
    Route::put('/update/status/video/{video}', [CourseController::class, 'statusVideo']);
    Route::put('/update/status/module/{module}', [CourseController::class, 'statusModule']);
    Route::delete('/delete/video/{video}', [CourseController::class, 'deteleVideo']);
    Route::delete('/delete/module/{module}', [CourseController::class, 'deleteModule']);
    Route::put('/status/course/{course}', [CourseController::class, 'statusCourse']);
    Route::post('/update/course/{course}', [CourseController::class, 'updateCourse']);
    Route::get('/get/video/{video}', [CourseController::class, 'getVideo']);
    Route::put('/update/video/{video}', [CourseController::class, 'updateVideo']);
    Route::get('/get/courses', [CourseController::class, 'getCourses']);
    Route::get('/get/courses/highlights', [CourseController::class, 'highlights']);
    Route::put('/update/module/{module}', [CourseController::class, 'updateModule']);


    // beta
    Route::get('/beta/courses', [betaController::class, 'getCourses']);
    Route::get('/beta/course/{course}', [betaController::class, 'getCourse']);
    Route::post('/beta/get/video/{video}', [betaController::class, 'getVideo']);
    Route::get('/beta/course-info/{course}', [betaController::class, 'getcourseInfo']);
    Route::post('/beta/concluded/video', [betaController::class, 'concludedVideo']);
    Route::post('/beta/store/comment', [betaController::class, 'StoreComment']);

//        traking

    Route::post('/traking/store', [trakingController::class, 'store']);
    Route::get('/traking/get', [trakingController::class, 'getPixel']);
    Route::post('/traking/get/all', [trakingController::class, 'getPixels']);
    Route::put('/tracking/status/{pixel}', [trakingController::class, 'statusPixel']);
    Route::put('/tracking/updatePixel/{pixel}', [trakingController::class, 'updatePixel']);
    Route::post('/tracking/exportleads', [trakingController::class, 'exportLeads']);
    Route::delete('/tracking/deletepixel/{pixel}', [trakingController::class, 'deletePixel']);
    Route::get('/tracking/getdata', [trakingController::class, 'getData']);


//    NOTIONS
    Route::post('/notion/store', [notionsController::class, 'store']);
    Route::get('/notions', [notionsController::class, 'getNotions']);
    Route::get('/notionsall', [notionsController::class, 'allNotions']);
    Route::get('/notion/{notion}', [notionsController::class, 'getNotion']);
    Route::put('/notion/{notion}', [notionsController::class, 'uptate']);
    Route::delete('/notion/{notion}', [notionsController::class, 'delete']);

//    FAFORITE
    Route::get('/favorite', [favoriteController::class, 'all']);
    Route::put('/favorite/{favorite}', [favoriteController::class, 'status']);
    Route::delete('/favorite/{favorite}', [favoriteController::class, 'delete']);
    Route::post('/favorite/store', [favoriteController::class, 'store']);


//DESTAQUES
    Route::get('/highlights', [highlightControoler::class, 'all']);
    Route::put('/highlights/store', [highlightControoler::class, 'highlightsStore']);
    Route::delete('/highlights/delete', [highlightControoler::class, 'delete']);
    Route::post('/store/header', [highlightControoler::class, 'storeheader']);
    Route::put('/status/header/{header}', [highlightControoler::class, 'statusHeader']);
    Route::delete('/header/{header}', [highlightControoler::class, 'deleteHeader']);




});
