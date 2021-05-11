<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Course;
use App\Models\Module;
use App\Models\Progress;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function GuzzleHttp\Promise\all;

class betaController extends Controller
{
    public function getCourses()
    {
        $courses = Course::where('status', 1)->get(['cover', 'id']);
        return response()->json($courses);
    }

    public function getClassInfo(Request $request)
    {
        $course = Course::where('id', $request->course)->first(['name', 'id', 'user_id', 'headline', 'desc', 'cover']);
        $producer = User::where('id', $course->user_id)->first(['username']);

        $data['course'] = $course;
        $data['producer'] = $producer;

        return response()->json($data);
    }


    public function getCourse(Request $request)
    {
        $course = Course::with(['modules' => function ($query) {
            $query->where('status', 1);
            $query->with(['videos' => function ($query) {
                $query->with('concluded');
            }]);
        }])->where('id', $request->course)->first();



        $producer = User::where('id', $course->user_id)->first(['cover', 'username', 'bio']);

        $data['course'] = $course;
        $data['producer'] = $producer;

        return response()->json($data);
    }

    public function getClass(Request $request)
    {
        // return response()->json($request->all());

        $class = Video::with('concluded')->find($request->class);
        $class->views = $class->views +1;

        $class->save();


        if (Progress::where('video_id', $class->id)->first()) {
            $progress = Progress::where('video_id', $class->id)->first();
            $progress->last_view =  date("m.d.y");;
            $progress->save();
        } else {
            $progress = new Progress();
            $progress->user_id = Auth::user()->id;
            $progress->video_id = $request->class;
            $progress->course_id = $class->course_id;
            $progress->last_view =  date("m.d.y");
            $progress->concluded = 0;
            $progress->producer_id = $class->user_id;
            $progress->save();
        }

        $comments = Comment::with('user')->where('video_id', $class->id)->get();


        $modules = Module::where('status', 1)->where('course_id', $class->course_id)->with(['videos' => function ($query) {
            $query->where('status', 1);
            $query->with('concluded');
        }])->get();

        $list = [];

        foreach ($modules as $module) {
            foreach($module->videos as $video){
                if(!empty($module)){
                    $list[] = $video;
                   }
            }


        }
        $data['modules'] = $modules;
        $data['class'] = $class;
        $data['classes'] = $list;
        $data['comments'] = $comments;

        return response()->json($data);
    }

    public function concludedClass(Request $request)
    {
        $course = Course::where('id', $request->course)->first(['user_id']);

        if (Progress::where('video_id', $request->class)->where('user_id', Auth::user()->id)->first()) {
            $progress = Progress::where('video_id', $request->class)->first();
            $progress->concluded = !$progress->concluded;
            $progress->save();
        } else {
            // $progress = new Progress();
            // $progress->user_id = Auth::check();
            // $progress->video_id = $request->class;
            // $progress->course_id = $request->course;
            // $progress->concluded = 1;
            // $progress->producer_id = $course->user_id;

            // $progress->save();
        }

        return response()->json($progress->concluded);
    }


    public function StoreComment(Request $request)
    {
        $course = Course::where('id', $request->course)->first(['user_id']);
        $comment = new Comment();
        $comment->user_id =  Auth::user()->id;
        $comment->course_id = $request->course;
        $comment->video_id = $request->video;
        $comment->comment = $request->comment;
        $comment->producer_id = $course->user_id;
        $comment->save();

        return response()->json('success');
    }
}
