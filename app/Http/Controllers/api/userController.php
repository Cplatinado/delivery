<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\suport\Cropper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\api\newAdm as newAdm;
use App\Http\Requests\api\newSuport as newSuport;
use App\Http\Requests\api\newProducer as newProducer;
use App\Http\Requests\api\UpdateProfile as UpdateProfile;


class userController extends Controller
{
    public function getUsers()
    {
        $users = User::all(['id', 'username', 'email', 'office']);

        return response()->json($users);
    }


    public function storeAdm(newAdm $request)
    {
        $user = new User();
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->document = $request->document;
        $user->password = $request->cpf;
        $user->cpf = $request->cpf;
        $user->birthday = $request->birthday;
        $user->zipcode = $request->zipcode;
        $user->country = $request->country;
        $user->state = $request->state;
        $user->city = $request->city;
        $user->office = $request->office;
        $user->save();


        if (!empty($request->file('cover'))) {

            $user->cover = $request->file('cover')->store('user');
            $user->save();
        }


        return response()->json('success');
    }


    public function storeProducer(newProducer $request)
    {
        $user = new User();
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->document = $request->document;
        $user->password = $request->cpf;
        $user->cpf = $request->cpf;
        $user->birthday = $request->birthday;
        $user->zipcode = $request->zipcode;
        $user->country = $request->country;
        $user->state = $request->state;
        $user->city = $request->city;
        $user->office = 'Produtor';
        $user->save();


        if (!empty($request->file('cover'))) {

            $user->cover = $request->file('cover')->store('user');
            $user->save();
        }
    }

    public function storeSuport(newSuport $request)
    {
        $user = new User();
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->document = $request->document;
        $user->password = $request->cpf;
        $user->cpf = $request->cpf;
        $user->birthday = $request->birthday;
        $user->zipcode = $request->zipcode;
        $user->country = $request->country;
        $user->state = $request->state;
        $user->city = $request->city;
        $user->office = 'Suporte';
        $user->save();


        if (!empty($request->file('cover'))) {

            $user->cover = $request->file('cover')->store('user');
            $user->save();
        }
    }


    public function updateProfile(UpdateProfile $request)
    {
        $user = User::find(Auth::user()->id);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->document = $request->document;
        $user->cpf = $request->cpf;
        $user->birthday = $request->birthday;
        $user->zipcode = $request->zipcode;
        $user->country = $request->country;
        $user->state = $request->state;
        $user->city = $request->city;
        $user->bio = $request->bio;
        $user->save();


        if (!empty($request->file('cover'))) {
            Storage::delete($user->cover);
            Cropper::flush($user->cover);

            $user->cover = $request->file('cover')->store('user');
            $user->save();
        }

        return response()->json($user);
    }

}
