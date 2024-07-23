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
        $league_logo = null;

        $validator = Validator::make($request->all(), [
            'league_name' => ['required', 'unique:leagues,name'],
            'league_logo' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($request->file('league_logo')) {
            $file = $request->file('league_logo');
            $path = $file->store('league_logos', 'public');
            $league_logo = url('storage/' . $path);
        } else {
            $url = $request->input('league_logo');
            $league_logo = $url;
        }
        $league = League::create([
            'name' => $request->league_name,
            'logo' => $league_logo,
        ]);
        return redirect()->back()->with('success', 'League Add Success');
    }

    public function edit($id)
    {
        $league = League::find($id);
        return view('League.edit-league', compact('league'));
    }

    public function update(Request $request, $id)
    {
        $league_logo = null;

        $validator = Validator::make($request->all(), [
            'league_name' => ['required', 'unique:leagues,name'],
            'league_logo' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($request->file('league_logo')) {
            $file = $request->file('league_logo');
            $path = $file->store('league_logos', 'public');
            $league_logo = url('storage/' . $path);
        } else {
            $url = $request->input('league_logo');
            $league_logo = $url;
        }
        $league = League::findOrFail($id);
        $league->name = $request->input('league_name');
        $league->logo = $league_logo;
        $league->save();

        return redirect()->route('league.index')->with('success', 'League updated successfully.');
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
