<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public $validator = null;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|min:5|max:255',
            'content' => 'required',
        ];
    }

    /**
     * Prepare the data for validation
     */
    protected function prepareForValidation(): void 
    {
        $this->merge([
            'image' => $this->file('image')->hashName(),
            'title' => $this->input('title'),
            'content' => $this->input('content'),
        ]);
    } 

    public function passedValidation()
    {
        // save the image
        $image = $this->file('image');
        $image->storeAs('public/posts', $image->hashName());
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }
}
