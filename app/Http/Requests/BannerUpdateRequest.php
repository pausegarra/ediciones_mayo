<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerUpdateRequest extends FormRequest
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
            'file' => 'max:1024|mimes:jpg,png,jpeg|nullable',
            'title' => 'mimes:svg|nullable'
        ];
    }

    public function getFile()
    {
        return $this->file('file');
    }

    public function getTitle()
    {
        return $this->file('title');
    }
}
