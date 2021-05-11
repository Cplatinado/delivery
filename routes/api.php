<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\aclController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\betaController;
use App\Http\Controllers\api\CourseController;
use App\Http\Controllers\api\userController;
use App\Http\Controllers\api\userControoler;

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

    Route::post('login',[AuthController::class, 'login']);


    Route::group(['middleware' => ['Jwt']], function(){
        Route::post('/composer require coffeecode/cropper', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::post('/me', [AuthController::class, 'me']);


        // ACL
        Route::post('/store/role',[aclController::class, 'storeRole']);
        Route::post('/store/permission',[aclController::class, 'storePermission']);

        Route::post('/get/roles',[aclController::class, 'getRoles']);
        Route::post('/get/permissions',[aclController::class, 'getPermissions']);
        Route::post('/get/permissions/sync',[aclController::class, 'permissionSync']);

        Route::post('/get/user',[userController::class, 'getUsers']);
        Route::post('/sync/roles',[aclController::class, 'rolesSync']);

        Route::post('/get/adms',[aclController::class, 'getAdmins']);





        // user
        Route::post('/store/adm',[userController::class, 'storeAdm']);
        Route::post('/store/producer',[userController::class, 'storeProducer']);
        Route::post('/store/suport',[userController::class, 'storeSuport']);
        Route::post('/update/profile',[userController::class, 'updateProfile']);


        // Course
        Route::post('/store/course',[CourseController::class, 'storeCourse']);
        Route::post('/store/module',[CourseController::class, 'storeModule']);
        Route::post('/get/course',[CourseController::class, 'getCouse']);
        Route::post('/store/class',[CourseController::class, 'storeClass']);
        Route::post('/update/status/class',[CourseController::class, 'statusClass']);
        Route::post('/update/status/module',[CourseController::class, 'statusModule']);
        Route::post('/delete/class',[CourseController::class, 'deleteClass']);
        Route::post('/delete/module',[CourseController::class, 'deleteModule']);
        Route::post('/status/course',[CourseController::class, 'statusCourse']);
        Route::post('/update/course',[CourseController::class, 'updateCourse']);
        Route::post('/get/class',[CourseController::class, 'getClass']);
        Route::post('/update/class',[CourseController::class, 'updateClass']);
        Route::post('/get/courses',[CourseController::class, 'getCourses']);
        Route::post('/update/module',[CourseController::class, 'updateModule']);


        // beta
        Route::post('/beta/get/courses',[betaController::class, 'getCourses']);
        Route::post('/beta/get/course',[betaController::class, 'getCourse']);
        Route::post('/beta/get/class',[betaController::class, 'getClass']);
        Route::post('/beta/get/class-info',[betaController::class, 'getClassInfo']);
        Route::post('/beta/concluded/class',[betaController::class, 'concludedClass']);
        Route::post('/beta/store/comment',[betaController::class, 'StoreComment']);


























    });
