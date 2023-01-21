<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Log;

class ProductUpdateRequest extends ApiFormRequest
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
        Log::debug("ID:: " . request()->id);
        return [
            'title' => 'required|string|max:100',
            'slug'  => 'required|string|max:120|unique:products,slug,' . request()->id,
            'price' => 'required|numeric',
            'image' => 'nullable|image'
        ];
    }
}
