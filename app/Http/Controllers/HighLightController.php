<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\League;
use App\Models\HighLight;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use App\Console\Commands\AutoHighLights;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AutoMatches\AutoHighLightController;

class HighLightController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $gg = new AutoHighLights();
        $matches = $gg->handle();
        $matches = HighLight::orderBy('match_time')->paginate(18);
        return view('highlight_actions.highlights', compact('matches'));
    }

    public function edit($id){
        $leagues = League::all();
        $match = HighLight::find($id);
        if (!$match) {
            session()->flash('error', 'Match not found.');
        }
        return view('highlight_actions.edit', compact('match', 'leagues'));
    }

    public function update(Request $request, $id)
    {

        $match = HighLight::findOrFail($id);

        if (!$match) {
            session()->flash('error', 'Match not found.');
            return redirect()->back();
        }

        $serverUrls = $request->input('server_url');
        $serverReferers = $request->input('server_referer');

        $servers = [];

        if ($match->servers) {
            $serversDatas = json_decode($match->servers, true);

            if (is_array($serverUrls)) {
                for ($i = 0; $i < count($serverUrls); $i++) {
                    if ($serverUrls[$i] !== null) {
                        $found = false;

                        foreach ($serversDatas as &$server) {
                            if ($serverUrls[$i] === $server['url'] && $server['new'] === false) {
                                $found = true;
                            }
                        }

                        if (!$found) {
                            $servers[] = [
                                'name' => 'Server ' . ($i + 1),
                                'url' => $serverUrls[$i],
                                'referer' => $serverReferers[$i],
                                'new' => true,
                            ];
                        } else {
                            $servers[] = [
                                'name' => 'Server ' . ($i + 1),
                                'url' => $serverUrls[$i],
                                'referer' => $serverReferers[$i],
                                'new' => false,
                            ];
                        }
                    }
                }
            }
        } else {
            for ($i = 0; $i < count($serverUrls); $i++) {
                if ($serverUrls[$i] !== null) {
                    $servers[] = [
                        'name' => 'Server ' . ($i + 1),
                        'url' => $serverUrls[$i],
                        'referer' => $serverReferers[$i],
                        'new' => true,
                    ];
                }
            }
        }

        $home_team_logo = null;

        if ($request->file('home_team_logo')) {
            $file = $request->file('home_team_logo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'logos/home_team_logos/' . $fileName;
            $home_team_logo = asset($filePath);
        } else {
            $url = $request->input('home_team_logo');
            $home_team_logo = $url;
        }

        $away_team_logo = null;

        if ($request->file('away_team_logo')) {
            $file = $request->file('away_team_logo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'logos/away_team_logos/' . $fileName;
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
            'match_status' => [Rule::in(['Live', 'Match', 'Highlight'])],
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

        $dateTimeString = $request->match_time;
        $timezone = Session::get('timezone');
        $dateTime = Carbon::parse($dateTimeString, $timezone);
        $match_time = $dateTime->timestamp;

        $homeTeamLogoUrl = $match->home_team_logo;
        $awayTeamLogoUrl = $match->away_team_logo;

        // Extract file names from URLs
        $homeTeamLogoFileName = basename($homeTeamLogoUrl);
        $awayTeamLogoFileName = basename($awayTeamLogoUrl);

        // Construct file paths
        $homeTeamLogoFilePath = public_path('logos/home_team_logos/' . $homeTeamLogoFileName);
        $awayTeamLogoFilePath = public_path('logos/away_team_logos/' . $awayTeamLogoFileName);

        $match_status = 'Highlight';
        $match = $match->update([
            'match_time' => $match_time,
            'home_team_name' => $request->home_team_name,
            'home_team_logo' => $home_team_logo,
            'home_team_score' => $request->home_team_score,
            'away_team_name' => $request->away_team_name,
            'away_team_logo' => $away_team_logo,
            'away_team_score' => $request->away_team_score,
            'league_name' => $leagueName,
            'league_logo' => $leagueLogo,
            'match_status' => $match_status,
            'servers' => json_encode($servers),
        ]);

        if ($match && $request->file('home_team_logo')) {
            $file = $request->file('home_team_logo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'logos/home_team_logos/' . $fileName;
            $file->move(public_path('logos/home_team_logos'), $fileName);
            if (File::exists($homeTeamLogoFilePath)) {
                File::delete($homeTeamLogoFilePath);
            }
        }

        if ($match && $request->file('away_team_logo')) {
            $file = $request->file('away_team_logo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'logos/away_team_logos/' . $fileName;
            $file->move(public_path('logos/away_team_logos'), $fileName);
            if (File::exists($awayTeamLogoFilePath)) {
                File::delete($awayTeamLogoFilePath);
            }
        }

        if ($homeTeamLogoUrl !== $home_team_logo) {
            if (File::exists($homeTeamLogoFilePath)) {
                File::delete($homeTeamLogoFilePath);
            }
        }

        if ($awayTeamLogoUrl !== $away_team_logo) {
            if (File::exists($awayTeamLogoFilePath)) {
                File::delete($awayTeamLogoFilePath);
            }
        }

        return redirect()
            ->back()
            ->with('success', 'Update Success');
    }

    public function destroy($id)
    {
        $match = Highlight::find($id);

        $homeTeamLogoUrl = $match->home_team_logo;
        $awayTeamLogoUrl = $match->away_team_logo;

        // Extract file names from URLs
        $homeTeamLogoFileName = basename($homeTeamLogoUrl);
        $awayTeamLogoFileName = basename($awayTeamLogoUrl);

        // Construct file paths
        $homeTeamLogoFilePath = public_path('logos/home_team_logos/' . $homeTeamLogoFileName);
        $awayTeamLogoFilePath = public_path('logos/away_team_logos/' . $awayTeamLogoFileName);

        // Delete the files if they exist
        if (File::exists($homeTeamLogoFilePath)) {
            File::delete($homeTeamLogoFilePath);
        }

        if (File::exists($awayTeamLogoFilePath)) {
            File::delete($awayTeamLogoFilePath);
        }

        $match->delete();
    }
}
