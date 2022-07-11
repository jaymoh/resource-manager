<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePdfPostRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'pdf_file' => 'required|file|mimes:pdf',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'title.required' => 'Please enter a title.',
            'title.string' => 'Title must be a string.',
            'title.max' => 'Title must be less than 255 characters.',
            'pdf_file.required' => 'Please upload a PDF file.',
            'pdf_file.file' => 'PDF file must be a file.',
            'pdf_file.mimes' => 'File must be a PDF file.',
        ];
    }
}
