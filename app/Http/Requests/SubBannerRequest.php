<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubBannerRequest extends FormRequest
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
            'icon'   => 'required|mimes:jpeg,jpg,png,svg',
            'title'  => 'required|string',
            'period' => 'required|date'
        ];
    }

    public function getTitle()
    {
        return $this->get('title');
    }

    public function getPeriod()
    {
        return $this->get('period');
    }

    public function getFile()
    {
        return $this->file('icon');
    }
}
