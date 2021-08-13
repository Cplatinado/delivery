<?php

namespace App\Http\Requests\api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class editCourse extends FormRequest
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
            'course' => (!empty($this->request->all()['id']) ? 'required|min:3|max:30 |unique:courses,name,' . $this->request->all()['id'] : 'required | min:3 | max:30| unique:courses,name,'),
            'category'=> 'required',
            'link' => (!empty($this->request->all()['id']) ? 'required | url |min:3| unique:courses,link,' . $this->request->all()['id'] : 'required |url| min:3 | unique:courses,link'),
            // 'avatar' => 'dimensions:ratio=16/9'
        ];
    }
}
