<?php

namespace App\Http\Controllers;

use DOMDocument;
use Carbon\Carbon;
use App\Models\League;
use App\Models\VnMatch;
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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AutoMatches\AutoMatchesController;
use App\Http\Controllers\AutoMatches\AutoVnMatchesController;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // set_time_limit(900);
        // // $gg = new AutoMatchesController();
        // $gg = new AutoVnMatchesController();
        // $matches = $gg->scrapeMatches();
        // return $matches;
        $matches = VnMatch::orderBy('match_time')->paginate(18);
        $route_match = 'match';
        return view('index', compact('matches', 'route_match'));
    }

    public function vn_matches()
    {
        $matches = VnMatch::orderBy('match_time')->paginate(18);
        $route_match = 'vn_match';
        return view('index', compact('matches', 'route_match'));
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
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $serverUrls = $request->input('server_url');
        $serverNames = $request->input('server_name');
        $serverReferers = $request->input('server_referer');

        $servers = [];

        for ($i = 0; $i < count($serverUrls); $i++) {
            if ($serverUrls[$i] !== null) {
                $servers[] = [
                    'name' => $serverNames[$i],
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
            $match = VnMatch::create([
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

            return redirect()->back()->with('success', 'Match Create Success');
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
                'is_auto_match' => false,
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

            return redirect()->back()->with('success', 'Highlight Create Success');
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
        $selectedMatch = request()->query('match');
        if ($selectedMatch === 'vn_match') {
            $match = VnMatch::find($id);
            $route_match = 'vn_match';
        } else {
            $match = VnMatch::find($id);
            $route_match = 'match';
        }

        if (!$match) {
            session()->flash('error', 'Match not found.');
        }
        return view('matches_actions.edit', compact('match', 'leagues', 'route_match'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        $selectedMatch = request()->query('match');
        if ($selectedMatch === 'vn_match') {
            $match = VnMatch::find($id);
        } else {
            $match = VnMatch::find($id);
        }
        if (!$match) {
            session()->flash('error', 'Match not found.');
            return redirect()->back();
        }

        $serverUrls = $request->input('server_url');
        $server_name = $request->input('server_name');
        $serverReferers = $request->input('server_referer');

        $newservers = [];
        $oldservers = [];
        $serversDatas = json_decode($match->servers, true);

        if ($match->servers) {
            if (is_array($serverUrls)) {
                for ($i = 0; $i < count($serverUrls); $i++) {
                    if ($serverUrls[$i] !== null) {
                        $found = false;

                        foreach ($serversDatas as &$server) {
                            if (isset($server['url']) && $serverUrls[$i] === $server['url'] && isset($server['new']) && $server['new'] === false) {
                                $found = true;
                            }
                        }

                        if (!$found) {
                            $newservers[] = [
                                'name' => $server_name[$i],
                                'url' => $serverUrls[$i],
                                'referer' => $serverReferers[$i],
                                'new' => true,
                            ];
                        } else {
                            $oldservers[] = [
                                'name' => $server_name[$i],
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
                    $newservers[] = [
                        'name' => $server_name[$i],
                        'url' => $serverUrls[$i],
                        'referer' => $serverReferers[$i],
                        'new' => true,
                    ];
                }
            }
        }

        // $serversData = $match->servers ? json_decode($match->servers, true) : [];

        // // Add the new servers to the front of the existing servers
        $servers = array_merge($newservers, $oldservers);

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
            return redirect()->back()->withErrors($validator)->withInput();
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
            'home_team_score' => $request->home_team_score,
            'away_team_name' => $request->away_team_name,
            'away_team_logo' => $away_team_logo,
            'away_team_score' => $request->away_team_score,
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

        return redirect()->back()->with('success', 'Update Success');
    }

    public function destroy($id)
    {
        $selectedMatch = request()->query('match');
        if ($selectedMatch === 'vn_match') {
            $match = VnMatch::find($id);
        } else {
            $match = VnMatch::find($id);
        }

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
            return back()->withErrors($validator)->withInput();
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return back()->with('success', 'Password change successfully.');
    }
}
