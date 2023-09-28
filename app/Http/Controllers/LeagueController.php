<?php

namespace App\Http\Controllers;

use App\Models\League;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class LeagueController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $leagues = League::all();
        return view('League.add-league', compact('leagues'));
    }

    public function store(Request $request)
    {
        // $league_logo = null;

        // if ($request->file('league_logo')) {
        //     $file = $request->file('league_logo');
        //     $fileName = time() . '_' . $file->getClientOriginalName();
        //     $filePath = 'logos/leagues_logos/' . $fileName; // Adjust the path
        //     $league_logo = asset($filePath);
        // } else {
        //     $url = $request->input('league_logo');
        //     $league_logo = $url;
        // }

        $validator = Validator::make($request->all(), [
            'league_name' => ['required', 'unique:leagues,name'],
            // 'league_logo' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $league = League::create([
            'name' => $request->league_name,
            'logo' => null,
        ]);

        // if ($league && $request->file('league_logo')) {
        //     $file = $request->file('league_logo');
        //     $fileName = time() . '_' . $file->getClientOriginalName();
        //     $filePath = 'logos/leagues_logos/' . $fileName; // Adjust the path
        //     $file->move(public_path('logos/leagues_logos'), $fileName);
        // }

        return redirect()
            ->back()
            ->with('success', 'League Add Success');
    }

    public function edit($id)
    {
        $league = League::find($id);
        return view('League.edit-league', compact('league'));
    }

    public function update(Request $request, $id)
    {
        $league = League::find($id);
        if (!$league) {
            session()->flash('error', 'Match not found.');
            return redirect()->back();
        }
        // $league_logo = null;
        // if ($request->file('league_logo')) {
        //     $file = $request->file('league_logo');
        //     $fileName = time() . '_' . $file->getClientOriginalName();
        //     $filePath = 'logos/leagues_logos/' . $fileName; // Adjust the path
        //     $league_logo = asset($filePath);
        // } else {
        //     $url = $request->input('league_logo');
        //     $league_logo = $url;
        // }

        $validator = Validator::make($request->all(), [
            'league_name' => ['required'],
            // 'league_logo' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $league_logo2 = $league->logo;

        $league_logo_name = basename($league_logo2);

        $league_logo_file_path = public_path('logos/leagues_logos/' . $league_logo_name);

        $league = $league->update([
            'name' => $request->league_name,
            'logo' => null,
        ]);

        // if ($league && $request->file('league_logo')) {
        //     $file = $request->file('league_logo');
        //     $fileName = time() . '_' . $file->getClientOriginalName();
        //     $filePath = 'logos/leagues_logos/' . $fileName; // Adjust the path
        //     $file->move(public_path('logos/leagues_logos'), $fileName);
        //     if (File::exists($league_logo_file_path)) {
        //         File::delete($league_logo_file_path);
        //     }
        // }

        // if ($league_logo2 !== $league_logo) {
        //     if (File::exists($league_logo_file_path)) {
        //         File::delete($league_logo_file_path);
        //     }
        // }

        return redirect()
            ->back()
            ->with('success', 'League Edit Success');
    }

    public function destroy($id)
    {
        $league = League::find($id);

        // $league_logo = $league->logo;

        // $league_logo_name = basename($league_logo);

        // $league_logo_file_path = public_path('logos/leagues_logos/' . $league_logo_name);

        // if (File::exists($league_logo_file_path)) {
        //     File::delete($league_logo_file_path);
        // }

        $league->delete();
    }
}
