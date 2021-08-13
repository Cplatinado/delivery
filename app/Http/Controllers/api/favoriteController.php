<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Notions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class favoriteController extends Controller
{


    public function all()
    {
        $notions = Favorite::with('course:id,name,cover,headline')
            ->where('user_id', Auth::id())->where('favorite', 1)->get(['id', 'course_id']);

        return response()->json($notions);
    }

    public function status(Favorite $favorite)
    {


        return response()->json('success');
    }


    public function store(Request $request)
    {
        if($favorite = Favorite::where('user_id', Auth::id())->where('course_id',$request->course)->first()){
            $favorite->favorite = !$favorite->favorite;
            $favorite->save();
            return  response()->json( $favorite->favorite);
        }
        $favorite = new Favorite();
        $favorite->user_id = Auth::id();
        $favorite->course_id = $request->course;
        $favorite->favorite = 1;
        $favorite->save();

        return response()->json($favorite);
    }

    public function delete(Favorite $favorite)
    {
        $favorite->delete();
        return response()->json('success');
    }
}
