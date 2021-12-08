<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTextRequest extends FormRequest
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
        return [
            'image'    => 'mimes:jpg,jpeg,png|nullable',
            'text'     => 'string|nullable',
            'doctor'   => 'string|nullable',
            'location' => 'string|nullable',
            'title'    => 'string|nullable'
        ];
    }

    public function getFile()
    {
        return $this->file('image');
    }

    public function getTitle()
    {
        return $this->get('title');
    }

    public function getDoctor()
    {
        return $this->get('doctor');
    }

    public function getLocation()
    {
        return $this->get('location');
    }

    public function getText()
    {
        return $this->get('text');
    }
}
