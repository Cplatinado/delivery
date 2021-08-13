<?php


namespace App\Services\sendMails;


use App\Jobs\confirmEmail;
use App\Jobs\confirmeUser;
use App\Jobs\confirmUser;
use App\Models\UserRecover;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class producerConfirm
{
    public function send(string $nome, string $email, string $id, string $office)
    {

        $userRecover = new UserRecover();
        $userRecover->user_id = $id;
        $userRecover->token = md5($id . $email . $nome . date('y-m-d H:i:S') . rand(0, 99));
        $userRecover->save();

        $data = new  \stdClass();
        $data->office = $office;
        $data->email = $email;
        $data->name = $nome;
        $data->link = env('LOCALHOST') . 'confirmar/email/' . $userRecover->token;

        if ($office == 'Assinante') {
//            confirmUser::dispatch($data);
            confirmeUser::dispatch($data);
//            Mail::send(new \App\Mail\confirmUser($data));


        }else{
            confirmEmail::dispatch($data);
        }


    }


}
