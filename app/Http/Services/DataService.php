<?php

namespace App\Http\Services;

use Illuminate\Http\Request;
use App\Models\FootballMatch;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class DataService
{
  public function store_matches($request){
    $home_team_logo = null;

    if ($request->file('home_team_logo')) {
        $file = $request->file('home_team_logo');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = 'logos/' . $fileName; // Adjust the path
        $file->move(public_path('logos'), $fileName); // Correct destination path
        $home_team_logo = asset($filePath);
    } else {
        $url = $request->input('home_team_logo');
        $home_team_logo = $url;
    }

    $away_team_logo = null;

    if ($request->file('away_team_logo')) {
        $file = $request->file('away_team_logo');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = 'logos/' . $fileName; // Adjust the path
        $file->move(public_path('logos'), $fileName); // Correct destination path
        $away_team_logo = asset($filePath);
    } else {
        $url = $request->input('away_team_logo');
        $away_team_logo = $url;
    }

    $validator = Validator::make($request->all(), [
        'match_time' => ['required'], // Validate timestamp format
        'home_team_name' => ['required', 'string'],
        'home_team_score' => ['nullable', 'integer'],
        'away_team_name' => ['required', 'string'],
        'away_team_score' => ['nullable', 'integer'],
        'match_status' => ['required', Rule::in(['Live', 'Match', 'Highlight'])],
        'server_url' => ['nullable', 'array'],
        'server_url.*' => ['nullable'],
        'server_referer' => ['nullable', 'array'],
        'server_referer.*' => ['nullable'],
    ]);

    $leagueInfo = explode(',', $request->league);
    $leagueName = trim($leagueInfo[0]);
    $leagueLogo = trim($leagueInfo[1]);

    if ($validator->fails()) {
        return redirect()
            ->back()
            ->withErrors($validator)
            ->withInput();
    }

    $serverUrls = $request->input('server_url');
    $serverReferers = $request->input('server_referer');

    $servers = [];

    for ($i = 0; $i < count($serverUrls); $i++) {
        $servers[] = [
            'name' => 'Server ' . ($i + 1),
            'url' => $serverUrls[$i],
            'referer' => $serverReferers[$i],
        ];
    }

    $dateTimeString = $request->match_time;
    $dateTime = Carbon::parse($dateTimeString);
    $match_time = $dateTime->timestamp * 1000;

    FootballMatch::create([
        'match_time' => $match_time,
        'home_team_name' => $request->home_team_name,
        'home_team_logo' => $home_team_logo,
        'home_team_score' => $request->home_team_score,
        'away_team_name' => $request->away_team_name,
        'away_team_logo' => $away_team_logo,
        'away_team_score' => $request->away_team_score,
        'league_name' => $leagueName,
        'league_logo' => $leagueLogo,
        'match_status' => $request->match_status,
        'servers' => json_encode($servers),
    ]);

    return response()->json(['message' => 'Record created successfully'], 201);
  }
}
