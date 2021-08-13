<?php

namespace App\Http\Controllers\api;

use App\Jobs\recoverPassword;
use App\Models\User;
use App\Models\UserRecover;
use App\Services\sendMails\producerConfirm;
use App\suport\Cropper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\api\newAdm as newAdm;
use App\Http\Requests\api\newSuport as newSuport;
use App\Http\Requests\api\newProducer as newProducer;
use App\Http\Requests\api\UpdateProfile as UpdateProfile;
use Spatie\Permission\Models\Role;
use App\Http\Requests\newPassword as newPassword;
use App\Http\Requests\newUser as newUSer;
use App\Http\Requests\CompleteRegister as  CompleteRegister;


class userController extends Controller
{
    public function getUsers()
    {
        $users = User::all(['id', 'username', 'email', 'office']);

        return response()->json($users);
    }


    public function storeUsers(newUSer $request)
    {
        if($request->password != $request->confirmPassword){
            return response()->json('password not match', 401);
        }
        if($request->terms == false){
            return response()->json('not terms', 401);
        }



        $user = new User();
        $user->email = $request->email;
        $user->firstname = $request->firstname;
        $user->office = "Assinante";
        $user->disabled = 1;
        $user->password = $request->password;
        $user->save();

        $role = Role::where('name',$user->office)->first();

        if(!empty($role)){
            $user->syncRoles($role);
            $user->office = $role->name;
            $user->save();
        }else{
            $user->syncRoles(null);
        }



        $sendConfirm = new producerConfirm();
        $sendConfirm->send($user->firstname, $user->email, $user->id,$user->office );

        return response()->json('success');



    }

    public function resendConfirm(Request $request)
    {
        $user = User::where('email', $request->email)->first();
//        return  response()->json($user);

        if(empty($user)){
            return  response()->json('not found',401);
        }
        if($user->email_verified == 1){
            return  response()->json('has verified',401);

        }

        $sendConfirm = new producerConfirm();
        $sendConfirm->send($user->firstname, $user->email, $user->id,$user->office );

        return response()->json('success');
    }

    public function completeRegister(CompleteRegister $request)
    {
        $userRecover = UserRecover::where('token',$request->token)->first();
        $user = User::find($userRecover->user_id);
        $user->username = $request->username;
        $user->lastname = $request->lastname;
        $user->birthday = $request->birthday;
        $user->save();

        $data['success'] = true;

        return response()->json($data);
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

        $role = Role::where('name',$user->office)->first();

        if(!empty($role)){
            $user->syncRoles($role);
            $user->office = $role->name;
            $user->save();
        }else{
            $user->syncRoles(null);
        }
        if (!empty($request->file('cover'))) {

            $user->cover = $request->file('cover')->store('user');
            $user->save();
        }

        $sendConfirm = new producerConfirm();
        $sendConfirm->send($user->firstname, $user->email, $user->id, $user->office);

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

        $role = Role::where('name',$user->office)->first();

        if(!empty($role)){
            $user->syncRoles($role);
            $user->office = $role->name;
            $user->save();
        }else{
            $user->syncRoles(null);
        }

        if (!empty($request->file('cover'))) {

            $user->cover = $request->file('cover')->store('user');
            $user->save();
        }


        $sendConfirm = new producerConfirm();
        $sendConfirm->send($user->firstname, $user->email, $user->id, $user->office);



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

        $role = Role::where('name',$user->office)->first();

        if(!empty($role)){
            $user->syncRoles($role);
            $user->office = $role->name;
            $user->save();
        }else{
            $user->syncRoles(null);
        }


        if (!empty($request->file('cover'))) {

            $user->cover = $request->file('cover')->store('user');
            $user->save();
        }
        $sendConfirm = new producerConfirm();
        $sendConfirm->send($user->firstname, $user->email, $user->id, $user->office);
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

    public function newPassord(newPassword $request)
    {
        $user = User::find(Auth::id());
        $user->password = $request->password;
        $user->save();

        return response()->json('success');

    }

    public function sendRecoverPassword(Request $request)
    {
//        return response()->json($request->all());

        $user = User::where('email', $request->email)->first();
        if(!$user){
            return response()->json(['error'=> 'email not found'],401);
        }
        $recover = new UserRecover();
        $recover->user_id = $user->id;
        $recover->token = md5($user->id .$user->email. date('y-m-d H:i:s'));
        $recover->expires = date('Y-m-d / H:i', strtotime('+30 minutes'));
        $recover->save();

        $data = new \stdClass();
        $data->email = $request->email;
        $data->name = $user->firstname;
        $data->link = env('LOCALHOST') . 'recuperar-senha/'. $recover->token;

        recoverPassword::dispatch($data);
//        Mail::send(new \App\Mail\recoverPassword($data));

        return response()->json('success');


    }

    public function recoverPassword(newPassword $request)
    {
        $confirmLogin = UserRecover::where('token', $request->token)->first();
//

        if($confirmLogin->expires < date('Y-m-d H:i')){
            return response()->json(['error'=> 'token expire'], 401);
        }
        if($confirmLogin->user == 1){
            return response()->json(['error'=> 'token used'], 401);

        }
        $confirmLogin->used = 1;
        $confirmLogin->save();

        $user = User::find($confirmLogin->user_id);


//
        if($request->password == $request->confirmPassword){
            $user->password = $request->password;
            $user->save();
            return response()->json('success');


        }else{
            return response()->json(['error'=> 'data not alowed'],401);
        }


    }


}
