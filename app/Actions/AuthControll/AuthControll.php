<?php


namespace App\Actions\AuthControll;


use App\Jobs\confirmLoginAdms;
use App\Mail\confirmLoginAdm;
use App\Mail\confirmLoguin;
use App\Models\UserRecover;
use http\Env\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Stevebauman\Location\Facades\Location;

class AuthControll
{
    public function ValidateLocation($user, $ip)
    {
        if ($user->last_ip_login != $ip) {
            $data = new \stdClass();
            $data->ip = $ip;
            $data->date = date('d/m/Y') . ' as  ' . date('H:i') . 'Hrs';
            $data->name = $user->firstname;
            $data->location = Location::get($ip);
            $data->email = $user->email;

//            Mail::send(new confirmLoguin($data));
            if ($user->office != 'Assinate') {

                $userRecover = new UserRecover();
                $userRecover->user_id = $user->id;
                $userRecover->token = md5($user->email .$user->id.$user->firstname . rand(0, 99) . date('y-d-m H:i:s'));
                $userRecover->expires = date('Y-m-d / H:i', strtotime('+30 minutes'));
                $userRecover->save();

                $data->token = $userRecover->token;
                $data->link = env('LOCALHOST') . 'confirmar-login/'. $userRecover->token;
//                Mail::send(new confirmLoginAdm($data));
                Mail::send(new confirmLoginAdm($data));
//                confirmLoginAdms::dispatch($data);


                return false;
            } else {


                return false;
            }



        }else{
            return true;
        }

    }
}
