<?php

namespace App\Http\Requests;

use App\DTOs\Players\CreatePlayerDTO;
use Illuminate\Foundation\Http\FormRequest;

class StorePlayerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'player_name' => 'required|unique:players|max:30|regex:/^[a-zA-Z0-9]+$/'
        ];
    }

    public function getDto(): CreatePlayerDTO
    {
        return new CreatePlayerDTO(
            $this->user()->id,
            $this->get('player_name')
        );
    }
}
