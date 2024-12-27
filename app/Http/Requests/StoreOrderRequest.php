<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
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
            "cart"=> "required|array",
            "address"=> "required|string",
            "phone"=> "required|string",
            "city_id"=> "required|integer|exists:cities,id",
            // 'img'=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
