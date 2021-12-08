<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TextsRequest extends FormRequest
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
            'text'     => 'required|string',
            'image'    => 'required|max:1024|mimes:jpg,png,jpeg',
            'doctor'   => 'required|string|nullable',
            'location' => 'required|string|nullable',
            'title'    => 'required|string|nullable'
        ];
    }

    public function getFile()
    {
        return $this->file('image');
    }

    public function getText()
    {
        return $this->get('text');
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
}


