<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class MatchRequest extends FormRequest
{
    public function rules()
    {
        return [
            // Define your validation rules here based on the input fields
            'match_time' => 'required', // Example
            'home_team_name' => 'required|string',
            'home_team_logo' => 'required',
            'home_team_score' => 'nullable|integer',
            'away_team_name' => 'required|string',
            'away_team_logo' => 'required',
            'away_team_score' => 'nullable|integer',
            'league_name' => 'required|string',
            'league_logo' => 'required',
            'match_status' => 'required|string',
            'servers' => 'required|array', // Validate servers as an array
            'servers.*.name' => 'required|string', // Validate each server's name
            'servers.*.url' => 'required', // Validate each server's URL
            'servers.*.referer' => 'required', // Validate each server's referer
        ];
    }
}
