<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class PlayerPointRequest extends FormRequest
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
        // $selectedPlayersObject = session()->get('selectedPlayers');
        // $selectedPlayers = $selectedPlayersObject->selectedPlayers;

        // $rules = [];

        // foreach ($selectedPlayers as $player) {
        //     $playerName = $player->playerName;

        //     $rules['status_' . $playerName] = 'required|integer|in:1,2,3';
        //     $rules['art5_' . $playerName] = 'nullable|boolean';
        //     $rules['art7_' . $playerName] = 'nullable|boolean';
        //     $rules['art10_' . $playerName] = 'nullable|boolean';
        //     $rules['art12_' . $playerName] = 'nullable|boolean';
        //     $rules['art15_' . $playerName] = 'nullable|boolean';
        //     $rules['art17_' . $playerName] = 'nullable|boolean';
        //     $rules['art20_' . $playerName] = 'nullable|boolean';
        //     $rules['art25_' . $playerName] = 'nullable|boolean';
        //     $rules['art30_' . $playerName] = 'nullable|boolean';
        //     $rules['gold_' . $playerName] = 'nullable|integer|min:0';
        //     $rules['tokens_' . $playerName] = 'nullable|integer|min:0';
        //     $rules['cards_' . $playerName] = 'nullable|integer|min:0';
        // }

        // return $rules;
        return [];
    }
}
