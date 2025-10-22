<?php 

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FavoriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'type' => 'required|string|in:movie,book',
            'id' => 'required|integer|min:1'
        ];
    }
    
    public function messages(): array
    {
        return [
            'type.required' => 'The item type is required.',
            'type.in' => 'The type must be either movie or book.',
            'id.required' => 'The item ID is required.',
            'id.integer' => 'The item ID must be a valid number.',
        ];
    }
}