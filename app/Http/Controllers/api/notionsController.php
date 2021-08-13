<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Notions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class notionsController extends Controller
{

    public function getNotions(Request $request)
    {
        $notions = Notions::where('vide_id', $request->video)->where('user_id', Auth::id())->get(['id', 'text']);

        return response()->json($notions);
    }

    public function allNotions()
    {
        $notions = Notions::with('course:id,name')->with('video:id,name')
            ->where('user_id', Auth::id())->get(['id', 'text','course_id', 'video_id']);

        return response()->json($notions);
    }

    public function store(Request $request)
    {
        if($request->text == null){
            return response()->json('empty',401);

        }
        $notion = new Notions();
        $notion->user_id = Auth::id();
        $notion->video_id = $request->video;
        $notion->course_id = $request->course;
        $notion->text = $request->text;
        $notion->save();

        return response()->json($notion);
    }

    public function getNotion(Notions $notion)
    {
        return response()->json($notion);
    }

    public function uptate(Notions $notion, Request $request)
    {
        $notion->text = $request->text;
        $notion->save();

        return response()->json('success');
    }

    public function delete(Notions $notion)
    {
        $notion->delete();
        return response()->json('success');
    }
}
