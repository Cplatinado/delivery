<?php

namespace App\Http\Requests\api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfile extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => (!empty($this->request->all()['id']) ? 'required | min:3 | max:30| unique:users,username,' . $this->request->all()['id'] : 'required | min:3 | max:30| unique:users,username,'),
            'email' => (!empty($this->request->all()['id']) ? 'required | min:3 |email| max:30| unique:users,email,' . $this->request->all()['id'] : 'required | min:3 | max:30| unique:users,email| email'),
            'cpf' =>(!empty($this->request->all()['id']) ? 'required | min:10 | max:11| unique:users,cpf,' . $this->request->all()['id'] : 'required | min:10 | max:11| unique:users,cpf'),
            'document' =>(!empty($this->request->all()['id']) ? 'required | min:8 | max:11| unique:users,document,' . $this->request->all()['id'] : 'required | min:8 | max:11| unique:users,document,'),
            'daybirth' => 'required | min:3 | max:10 ',
            'zipcode' => 'required | min:3 | max:9',
            'country' => 'required | min:3 | max:30| ',
            'state' => 'required | min:2 | max:30',
            'city' => 'required | min:3 | max:30',
        ];

    }
}
