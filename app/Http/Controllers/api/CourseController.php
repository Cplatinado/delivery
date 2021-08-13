<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\editClass;
use App\Http\Requests\api\editCourse;
use App\Models\Course;
use App\Models\Module;
use App\Models\Video;
use Faker\Core\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\newCourse as newCourse;
use App\Http\Requests\newModule as newModule;
use App\Models\Comment;
use App\Models\Progress;
use Illuminate\Validation\UnauthorizedException;
use phpDocumentor\Reflection\Types\Boolean;
use PhpParser\Node\Expr\AssignOp\Mod;
use PhpParser\Node\Scalar\String_;

class CourseController extends Controller
{
    public function getCourses()
    {

        if (!Auth::user()->hasPermissionTo('entrar central produtor')) {//
            return response()->json('not authorized', '401');
        }
//

        $course = Course::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get(['name', 'status', 'slug', 'id']);
        $videos = Video::where('user_id', Auth::user()->id)->get(['views']);

        $progress = Progress::where('producer_id', Auth::user()->id)->get(['concluded']);

        $qtd = Course::where('user_id', Auth::user()->id)->count();
        $comments = Comment::where('producer_id', Auth::user()->id)->get(['comment', 'video_id', 'course_id']);

        $data['progress'] = $progress;
        $data['videos'] = $videos;
        $data['comments'] = $comments;
        $data['course'] = $course;
        $data['qtd'] = $qtd;
        return response()->json($data);
    }

    public function highlights()
    {
        $courses = Course::all(['id','name']);

        return response()->json($courses);
    }

    public function storeCourse(newCourse $request)
    {

        if (!Auth::user()->hasPermissionTo('add curso')) {
//
            return response()->json('not authorized', '401');
        }

        $course = new Course();
        $course->name = $request->name;
        $course->category = $request->category;
        $course->link = $request->link;
        $course->user_id = Auth::user()->id;
        $course->save();


        return response()->json($course);

    }

    public function storeModule(newModule $request)
    {
        if (!Auth::user()->hasPermissionTo('add modulo')) {//
            return response()->json('not authorized', '401');
        }

        $course = Course::find($request->course);
        if ($course->user_id != Auth::id()) {
            return response()->json('not authorized', 401);
        }

        $module = new Module();
        $module->name = $request->module;
        $module->course_id = $request->course;
        $module->user_id = Auth::user()->id;
        $module->save();

        return response()->json($module);

    }

    public function updateModule(Module $module, Request $request)
    {
        if (!Auth::user()->hasPermissionTo('atualizar modulo')) {//
            return response()->json('not authorized', '401');
        }
        if ($module->user_id != Auth::id()) {
            return response()->json('not authorized', 401);
        }

        $module->name = $request->name;
        $module->save();

        return true;
    }

    public function getCouse(Course $course, Request $request)
    {
        if (!Auth::user()->hasPermissionTo('edit curso')) {//
            return response()->json('not authorized', '401');
        }

        if ($course->user_id != Auth::id()) {
            return response()->json('not authorized', 401);
        }




        $videos = $course->commentsList($course->id);
        $progress = Progress::where('course_id', $course->id)->get();
        $course = $course->contentCourse($course->id);

        $data['videos'] = $videos;
        $data['course'] = $course;
        $data['progress'] = $progress;

        return response()->json($data);
    }

    public function statusVideo(Video $video, Request $request)
    {
        if (!Auth::user()->hasPermissionTo('editar aula')) {//
            return response()->json('not authorized', '401');
        }
        if ($video->user_id != Auth::id()) {
            return response()->json('not authorized', 401);
        }

        $video->status = !$video->status;
        $video->save();
        return response()->json($video->status);
    }

    public function statusModule(Module $module, Request $request)
    {
        if (!Auth::user()->hasPermissionTo('editar aula')) {//
            return response()->json('not authorized', '401');
        }

        if ($module->user_id != Auth::id()) {
            return response()->json('not authorized', 401);
        }

        $module->status = !$module->status;
        $module->save();
        return response()->json($module->status);
    }

    public function deteleVideo(Video $video)
    {
        if (!Auth::user()->hasPermissionTo('editar aula')) {//
            return response()->json('not authorized', '401');
        }
        if ($video->user_id!= Auth::id()) {
            return response()->json('not authorized', 401);
        }

        $video->delete();
        return response()->json('success');

    }


    public function deleteModule(Module $module)
    {
        if (!Auth::user()->hasPermissionTo('editar aula')) {//
            return response()->json('not authorized', '401');
        }
        if ($module->user_id!= Auth::id()) {
            return response()->json('not authorized', 401);
        }

        $module->delete();
        return response()->json('success');

    }


    public function storeClass(editClass $request)
    {
        if (!Auth::user()->hasPermissionTo('add aula')) {//
            return response()->json('not authorized', '401');
        }
        $course = Course::find($request->course);
        if ($course->user_id!= Auth::id()) {
            return response()->json('not authorized', 401);
        }


        $class = new Video();
        $class->user_id = Auth::user()->id;
        $class->course_id = $request->course;
        $class->module_id = $request->module;
        $class->name = $request->name;
        $class->desc = $request->desc;
        $class->player = $request->player;
        $class->link = $request->link;
        $class->save();

        if (!empty($request->file('cover'))) {
            $class->cover = $request->file('cover')->store('course/' . $class->course_id);
            $class->save();
        }

        return response()->json('success');


    }

    public function statusCourse(Course $course, Request $request)
    {

        if (!Auth::user()->hasPermissionTo('edit curso')) {//
            return response()->json('not authorized', '401');
        }
        if ($course->user_id!= Auth::id()) {
            return response()->json('not authorized', 401);
        }

//

        $course->status = !$course->status;
        $course->save();
        return response()->json($course->status);
    }


    public function updateCourse(Course $course, editCourse $request)
    {
        if (!Auth::user()->hasPermissionTo('edit curso')) {//
            return response()->json('not authorized', '401');
        }
        if ($course->user_id!= Auth::id()) {
            return response()->json('not authorized', 401);
        }

        $course->name = $request->course;
        $course->category = $request->category;
        $course->link = $request->link;
        $course->desc = $request->desc;
        $course->headline = $request->headline;
        $course->pixel = $request->fb;
        $course->google = $request->google;
        $course->gtm = $request->gtm;
        $course->duration = $request->duration;
        $course->save();


        if (!empty($request->file('avatar'))) {


            $course->cover = $request->file('avatar')->store('course/' . $course->id);
            $course->save();
        }

        return response()->json($course->cover);


    }

    public function getVideo(Video $video, Request $request)
    {
        if (!Auth::user()->hasPermissionTo('editar aula')) {//
            return response()->json('not authorized', '401');
        }

        if ($video->user_id!= Auth::id()) {
            return response()->json('not authorized', 401);
        }

        return response()->json($video);
    }

    public function updateVideo(Video $video , editClass $request)
    {//
        if (!Auth::user()->hasPermissionTo('editar aula')) {//
            return response()->json('not authorized', '401');
        }
        if ($request->player == '1') {
            if (strlen($request->link) != 11) {
                return response()->json('Id de video invalido', 422);
            }
        }
        if ($video->user_id!= Auth::id()) {
            return response()->json('not authorized', 401);
        }


        $video->name = $request->name;
        $video->desc = $request->desc;
        $video->player = $request->player;
        $video->link = $request->link;
        $video->save();

        if (!empty($request->file('cover'))) {


            $video->cover = $request->file('cover')->store('course/' . $video->course_id);
            $video->save();
        }

        return response()->json($video->cover);
    }




    //
}
