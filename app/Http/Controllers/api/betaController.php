<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Mail\confirmLoguin;
use App\Models\Comment;
use App\Models\Course;
use App\Models\highlight;
use App\Models\Module;
use App\Models\Notions;
use App\Models\Progress;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;
use function GuzzleHttp\Promise\all;

class betaController extends Controller
{
    public function getCourses()
    {

        $courses = Course::where('status', 1)->get(['cover', 'id']);
        $header = highlight::where('type', 1)->where('active', 1)->first();
        $highlights = highlight::where('type', 0)->with('course:id,cover')->get();


        $data['courses'] = $courses;
        $data['header'] = $header;
        $data['highlights'] = $highlights;
        return response()->json($data);
    }

    public function getcourseInfo(Course $course, Request $request)
    {

//        $course = Course::where('id', $request->course)->first(['name', 'id', 'user_id', 'headline', 'desc', 'cover']);
        $course = $course->getInfo($course->id);
        $producer = User::where('id', $course->user_id)->first(['username']);

        $data['course'] = $course;
        $data['producer'] = $producer;

        return response()->json($data);
    }


    public function getCourse(Course $course)
    {
        $course = $course->betaContentCourse($course->id);


        $producer = User::where('id', $course->user_id)->first(['cover', 'username', 'bio']);

        $data['course'] = $course;
        $data['producer'] = $producer;

        return response()->json($data);
    }

    public function getVideo(Video $video, Request $request)
    {

        $videoData = $video->getConcluded($video->id);
        $video->views = $video->views + 1;
        $video->save();

        $course = Course::where('id', $video->course_id)->first(['link', 'slug']);


        if (Progress::where('video_id', $video->id)->first()) {
            $progress = Progress::where('video_id', $video->id)->first();
            $progress->last_view = date("Y-m-d");
            $progress->save();
        } else {
            $progress = new Progress();
            $progress->user_id = Auth::user()->id;
            $progress->video_id = $request->class;
            $progress->course_id = $video->course_id;
            $progress->last_view = date("Y-m-d");
            $progress->concluded = 0;
            $progress->producer_id = $video->user_id;
            $progress->save();
        }

        $comments = Comment::with('user:id,username,cover')->where('video_id', $video->id)->get();

        $notions = Notions::where('user_id', Auth::id())->where('video_id', $video->id)->get();


        $modules = Module::where('status', 1)->where('course_id', $video->course_id)->with(['videos' => function ($query) {
            $query->where('status', 1);
            $query->with('concluded:id,video_id,concluded');


        }])->get(['name', 'id']);

        $list = [];

        foreach ($modules as $module) {
            foreach ($module->videos as $video) {
                if (!empty($module)) {
                    $list[] = $video;
//
                }

            }
        }
        $data['modules'] = $modules;
        $data['class'] = $videoData;
        $data['classes'] = $list;
        $data['comments'] = $comments;
        $data['course'] = $course;
        $data['notions'] = $notions;
        return response()->json($data);
    }


    public function concludedVideo(Request $request)
    {

        if (Progress::where('video_id', $request->class)->where('user_id', Auth::user()->id)->first()) {
            $progress = Progress::where('video_id', $request->class)->first();
            $progress->concluded = !$progress->concluded;
            $progress->save();
        }

        return response()->json($progress->concluded);
    }


    public function StoreComment(Request $request)
    {
        $course = Course::where('id', $request->course)->first(['user_id']);
        $comment = new Comment();
        $comment->user_id = Auth::user()->id;
        $comment->course_id = $request->course;
        $comment->video_id = $request->video;
        $comment->comment = $request->comment;
        $comment->producer_id = $course->user_id;
        $comment->save();

        return response()->json('success');
    }
}
