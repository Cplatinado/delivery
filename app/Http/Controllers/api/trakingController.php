<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Traking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\Csv\Writer;

class trakingController extends Controller
{
    public function store(Request $request)
    {

        if (!Auth::user()->hasPermissionTo('criar pixel')) {//
            return response()->json('not authorized', '401');
        }
        $traking = new Traking();
        $traking->source = $request->source;
        $traking->name = $request->name;
        $traking->code = $request->code;
        $traking->save();

        return response()->json($traking);
    }

    public function getPixels()
    {

        $traking = Traking::where('status', 1)->get(['code', 'source']);
        return response()->json($traking);
    }

    public function getPixel(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('ver pixel')) {
            return response()->json('not authorized', '401');
        }

        $traking = Traking::where('source', $request->source)->get();
       foreach ($traking as $item){
           $item->loading = false;
       }

        return response()->json($traking);
    }

    public function statusPixel(Traking $pixel, Request $request)
    {

        if (!Auth::user()->hasPermissionTo('editar pixel')) {
            return response()->json('not authorized', '401');
        }

        $pixel->status = !$pixel->status;
        $pixel->save();
        return response()->json($pixel->status);
    }

    public function updatePixel(Traking $pixel, Request $request)
    {

        if (!Auth::user()->hasPermissionTo('editar pixel')) {
            return response()->json('not authorized', '401');
        }

        $pixel->code = $request->code;
        $pixel->name = $request->name;
        $pixel->save();

        return response()->json('success');
    }

    public function exportLeads()
    {

        if (!Auth::user()->hasPermissionTo('exportar leads')) {
            return response()->json('not authorized', '401');
        }
        $users = User::all();
        $csv = Writer::createFromString();
        $csv->insertOne(['nome', 'email']);

        foreach ($users as $user) {
            $csv->insertOne([$user->firstname, $user->email]);

        }
        return response($csv->output('leads.csv'));
    }

    public function deletePixel(Traking $pixel ,Request $request)
    {

        if (!Auth::user()->hasPermissionTo('excluir pixel')) {
            return response()->json('not authorized', '401');
        }
        $pixel->delete();

        return response()->json('success');
    }

    public function getData()
    {


        if (!Auth::user()->hasPermissionTo('ver pixel')) {
            return response()->json('not authorized', '401');
        }
        $pixel = Traking::all();
        $data['fb'] = 0;
        $data['gmt'] = 0;
        $data['analytics'] = 0;
        $data['gTags'] = 0;


        foreach ($pixel as $item) {
            if ($item->source == 'fb') {
                $data['fb']++;
            } elseif ($item->source == 'gtm') {
                $data['gmt']++;
            } elseif ($item->source == 'analytics') {
                $data['analytics']++;
            } elseif ($item->source == 'gTags') {
                $data['gTags']++;
            }

        }

        $leads = User::where('office', 'Aluno')->get(['firstname', 'email']);
        $data['leads'] = $leads;


        return response()->json($data);
    }
}
