<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\editClass;
use App\Http\Requests\api\editCourse;
use App\Models\Course;
use App\Models\Module;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\newCourse as newCourse;
use App\Http\Requests\newModule as newModule;
use App\Models\Comment;
use App\Models\Progress;

class CourseController extends Controller
{
    public function getCourses()
    {
        // sleep(3);
        $course = Course::where('user_id',Auth::user()->id)->orderBy('id','desc')->get(['name','user_id', 'status', 'slug','id']);
        $videos = Video::where('user_id', Auth::user()->id)->get();

        $progress = Progress::where('producer_id', Auth::user()->id)->get();

        $qtd = Course::where('user_id',Auth::user()->id)->count();
        $comments = Comment::where('producer_id', Auth::user()->id)->get();

        $data['progress'] = $progress;
        $data['videos'] = $videos;
        $data['comments'] = $comments;
        $data['course'] =$course;
        $data['qtd'] = $qtd;
        return response()->json($data);
    }

    public function storeCourse(newCourse $request)
    {
        // return response()->json($request->all());

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
        $module = new Module();
        $module->name = $request->module;
        $module->course_id = $request->course;
        $module->user_id = Auth::user()->id;
        $module->save();

        return response()->json($module);

    }

    public function updateModule(Request $request)
    {
        $module = Module::find($request->module);
        $module->name = $request->name;
        $module->save();

        return response()->json('success');
    }

    public function getCouse(Request $request)
    {
        $course = Course::with(['modules'=> function($query){
            $query->with('videos');
        }])->where('slug', $request->course)->first();

        return response()->json($course);

    }

    public function statusClass(Request $request)
    {
        $class = Video::find($request->class);
        $class->status = !$class->status;
        $class->save();
         return response()->json($class->status);
    }

    public function statusModule(Request $request)
    {
        $module = Module::find($request->module);
        $module->status = !$module->status;
        $module->save();
         return response()->json($module->status);
    }
    public function deleteClass(Request $request)
    {
        $class = Video::find($request->class);
        $class->delete();
        return response()->json('success');

    }


    public function deleteModule(Request $request)
    {
        $module = Module::find($request->module);
        $module->delete();
        return response()->json('success');

    }



    public function storeClass(Request $request)
    {
        // return response()->json($request->all());

        $class = new Video();
        $class->user_id = Auth::user()->id;
        $class->course_id =  $request->course;
        $class->module_id = $request->module;
        $class->name = $request->name;
        $class->desc =$request->desc;
        $class->player = $request->player;
        $class->link = $request->link;
        $class->save();

        if (!empty($request->file('cover'))) {


            $class->cover = $request->file('cover')->store('course/'.$class->course_id);
            $class->save();
        }

        return response()->json('success');



    }

    public function statusCourse(Request $request)
    {
        $course = Course::where('slug', $request->course)->first();
        $course->status = !$course->status;
        $course->save();
        return response()->json('success');
    }


    public function updateCourse(editCourse $request)
    {
        // return response()->json($request->all());

        $course = Course::where('slug', $request->course)->first();
        $course->name = $request->course;
        $course->category = $request->category;
        $course->link = $request->link;
        $course->desc = $request->desc;
        $course->headline = $request->headline;
        $course->pixel = $request->fb;
        $course->google = $request->google;
        $course->gtm = $request->gtm;
        $course->save();


        if (!empty($request->file('avatar'))) {


            $course->cover = $request->file('avatar')->store('course/'.$course->id);
            $course->save();
        }

        return response()->json($course->cover);





    }
    public function getClass(Request $request)
    {
        $class = Video::find($request->class);
        return response()->json($class);
    }

    public function updateClass(editClass $request)
    {
        $class = video::find($request->class);
        $class->user_id = Auth::user()->id;
        $class->course_id =  $request->course;
        $class->module_id = $request->module;
        $class->name = $request->name;
        $class->desc =$request->desc;
        $class->player = $request->player;
        $class->link = $request->link;
        $class->save();

        if (!empty($request->file('cover'))) {


            $class->cover = $request->file('cover')->store('course/'.$class->course_id);
            $class->save();
        }

        return response()->json($class->cover);
    }
    //
}
