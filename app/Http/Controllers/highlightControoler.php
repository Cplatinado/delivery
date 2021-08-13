<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\highlight;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class highlightControoler extends Controller
{
    public function all()
    {
        $highligth = highlight::with('course:id,name,cover')->get();

        return response()->json($highligth);
    }

    public function highlightsStore(Request $request)
    {
        $highligth = new highlight();
        $highligth->course_id = $request->course;
        $highligth->order = $request->order;
        $highligth->type = 0;
        $highligth->save();

        $course = Course::where('id', $highligth->course_id)->first(['id', 'cover','name']);
        $data['course'] = $course;
        $data['order'] = $request->order;


        return response()->json($data);
    }

    public function delete(Request $request)
    {
        $highligth = highlight::where('order', $request->order)->first();
        $highligth->delete();

        return response()->json('success');
    }

    public function storeheader(Request $request): JsonResponse
    {

        $header = new highlight();
        $header->title = $request->title;
        $header->subtitle = $request->subtitle;
        $header->type = 1;
        $header->save();

        if (!empty($request->file('cover'))) {
            $header->cover = $request->file('cover')->store('header/' . $header->id);
            $header->save();
        }

        return response()->json($header);
    }

    public function statusHeader(highlight $header, Request $request)
    {

        $all = highlight::where('type', 1)->get();
        foreach ($all as $item) {
          $item->active = 0;
          $item->save();
        }
//        $header

        $header->active = 1;
        $header->save();

        return response()->json('success');

    }

    public function deleteHeader(highlight $header)
    {
        $header->delete();
        return response()->json('success');
    }
}
