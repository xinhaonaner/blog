<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'file' => 'required|image',
        ];
    }

    public function messages() {
        return [
            'file.required' => '图片必传',
            'file.image' => '图片格式有误',
        ];
    }
}
