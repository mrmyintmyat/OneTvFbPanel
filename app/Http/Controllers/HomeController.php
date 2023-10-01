<?php

namespace App\Http\Controllers;

use DOMDocument;
use Carbon\Carbon;
use App\Models\League;
use App\Models\AutoMatch;
use App\Models\HighLight;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use App\Models\FootballMatch;
use Illuminate\Validation\Rule;
use App\Http\Services\DataService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Console\Commands\AutoMatches;
use App\Console\Commands\MyCustomTask;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AutoMatches\AutoMatchesController;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // $gg = new AutoMatches();
        // $gg->handle();
        // return $matches;

        // $matches = [
        //     [
        //         'match_time' => '1695429050000',
        //         'home_team_name' => 'Al Nassr',
        //         'home_team_logo' => 'https://img.bingsport.com/static/common/img/soccer/teams/10/2506.png',
        //         'away_team_name' => 'Al Ahli Jeddah',
        //         'away_team_logo' => 'https://img.bingsport.com/static/common/img/soccer/teams/20/9908.png',
        //         'league_name' => 'Pro League',
        //         'league_logo' => null,
        //         'match_status' => 'Match',
        //         'servers' => [],
        //         'is_auto_match' => true,
        //     ],
        //     [
        //         'match_time' => '1695393050000',
        //         'home_team_name' => 'RANS Nusantara',
        //         'home_team_logo' => 'https://img.bingsport.com/static/common/img/soccer/teams/27/27611.png',
        //         'away_team_name' => 'Persis Solo',
        //         'away_team_logo' => 'https://img.bingsport.com/static/common/img/soccer/teams/6/27622.png',
        //         'league_name' => 'Liga 1',
        //         'league_logo' => null,
        //         'match_status' => 'Match',
        //         'servers' => [],
        //         'is_auto_match' => true,
        //     ],
        // ];
        // return 'success';

        // $Automatches = AutoMatch::orderBy('match_time')->paginate(18);
        // $matches = FootballMatch::orderBy('match_time')->paginate(18);

        // $matches = [];

        $matches = FootballMatch::orderBy('match_time')->paginate(18);
        // $matches2 = AutoMatch::orderBy('match_time');

        // $matches = $matches1->union($matches2)->paginate(18);
        // $matches = $matches1->concat($matches2);
        // foreach ($matches1 as $match) {
        //     $matches[] = $match;
        // }

        // foreach ($matches2 as $match) {
        //     $matches[] = $match;
        // }
        return view('index', compact('matches'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        $leagues = League::all();
        return view('create-matches', compact('leagues'));
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
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
            if ($serverUrls[$i] !== null) {
                $servers[] = [
                    'name' => 'Server ' . ($i + 1),
                    'url' => $serverUrls[$i],
                    'referer' => $serverReferers[$i],
                    'new' => true,
                ];
            }
        }

        $dateTimeString = $request->match_time;
        $timezone = Session::get('timezone');
        $dateTime = Carbon::parse($dateTimeString, $timezone);
        $match_time = $dateTime->timestamp;

        if ($request->match_status === 'Match' || $request->match_status === 'Live') {
            $match = FootballMatch::create([
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
                'is_auto_match' => false,
            ]);

            if ($match && $request->file('home_team_logo')) {
                $file = $request->file('home_team_logo');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = 'logos/home_team_logos/' . $fileName; // Adjust the path
                $file->move(public_path('logos/home_team_logos'), $fileName);
            }

            if ($match && $request->file('away_team_logo')) {
                $file = $request->file('away_team_logo');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = 'logos/away_team_logos/' . $fileName; // Adjust the path
                $file->move(public_path('logos/away_team_logos'), $fileName);
            }

            return redirect()
                ->back()
                ->with('success', 'Match Create Success');
        } elseif ($request->match_status === 'Highlight') {
            $highlight = Highlight::create([
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

            if ($highlight && $request->file('home_team_logo')) {
                $file = $request->file('home_team_logo');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = 'logos/home_team_logos/' . $fileName; // Adjust the path
                $file->move(public_path('logos/home_team_logos'), $fileName);
            }

            if ($highlight && $request->file('away_team_logo')) {
                $file = $request->file('away_team_logo');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = 'logos/away_team_logos/' . $fileName; // Adjust the path
                $file->move(public_path('logos/away_team_logos'), $fileName);
            }

            return redirect()
                ->back()
                ->with('success', 'Highlight Create Success');
        }
    }

    // Display the specified resource.
    public function show($id)
    {
        // Your logic to fetch and display a single resource based on $id
    }

    // Show the form for editing the specified resource.

    public function edit($id)
    {
        $leagues = League::all();
        $match = FootballMatch::find($id);
        if (!$match) {
            session()->flash('error', 'Match not found.');
        }
        return view('matches_actions.edit', compact('match', 'leagues'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        $match = FootballMatch::find($id);

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

        $match = $match->update([
            'match_time' => $match_time,
            'home_team_name' => $request->home_team_name,
            'home_team_logo' => $home_team_logo,
            'home_team_score' => null,
            'away_team_name' => $request->away_team_name,
            'away_team_logo' => $away_team_logo,
            'away_team_score' => null,
            'league_name' => $leagueName,
            'league_logo' => $leagueLogo,
            'match_status' => $request->match_status,
            'servers' => json_encode($servers),
        ]);

        if ($match && $request->file('home_team_logo')) {
            $file = $request->file('home_team_logo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'logos/home_team_logos/' . $fileName; // Adjust the path
            $file->move(public_path('logos/home_team_logos'), $fileName);
            if (File::exists($homeTeamLogoFilePath)) {
                File::delete($homeTeamLogoFilePath);
            }
        }

        if ($match && $request->file('away_team_logo')) {
            $file = $request->file('away_team_logo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'logos/away_team_logos/' . $fileName; // Adjust the path
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
        $match = FootballMatch::find($id);

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

    public function pw_change_show_form()
    {
        return view('auth.passwords.password_change');
    }

    public function pw_change(Request $request)
    {
        $user = Auth::user();
        $currentPassword = $request->input('org_pw');

        if (!Hash::check($currentPassword, $user->password)) {
            return back()->withErrors(['org_pw' => 'The current password is incorrect.']);
        }

        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return back()->with('success', 'Password change successfully.');
    }
}
