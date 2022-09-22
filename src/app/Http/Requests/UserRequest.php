<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $route = \Request::route()->getName();

        return [
            'name' => 'required|max:50',
            'document' => ($route != 'users.update') ? 'required|unique:users' : 'required',
            'email' => ($route != 'users.update') ? 'required|email|max:255|unique:users' : 'required|email|max:255',
            'type' => 'required|in:CONSUMER,SELLER',
            'password' => 'required|max:50',
            'balance' => 'nullable|regex:/^\d+(\.\d{1,2})?$/'
        ];
    }

    protected function prepareForValidation()
    {
        $this->getInputSource()->replace([
            'name' => $this->request->get('name'),
            'document' => preg_replace('/\.|-|\//', '', strtolower($this->request->get('document'))),
            'email' => $this->request->get('email'),
            'type' => $this->request->get('type'),
            'password' => $this->request->get('password'),
            'balance' => $this->request->get('balance'),
        ]);
    }
}
