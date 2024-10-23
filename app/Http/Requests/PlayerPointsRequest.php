<?php

namespace App\Http\Requests;

use App\DTOs\NewGameParams\AllPlayersResultsDTO;
use App\DTOs\NewGameParams\OnePlayerResultDTO;
use App\Enums\ArtifactType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

class PlayerPointsRequest extends FormRequest
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
        return [];
    }
}
