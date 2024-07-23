<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $channels = Channel::paginate(17);
        return view('channel.index', compact('channels'));
    }

    public function create()
    {
        return view('channel.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'channel_name' => 'required|string|max:255',
            'channel_logo' => 'nullable',
            'server_url.*' => 'required|url',
            'server_header.*' => 'nullable',
        ]);

        $channel = new Channel();
        $channel->channel_name = $validated['channel_name'];

        if ($request->hasFile('channel_logo')) {
            $path = $request->file('channel_logo')->store('logos', 'public');
            $channel->channel_logo = env('APP_URL') . '/storage/' . $path;
        }else{
            $channel->channel_logo = $request->channel_logo;
        }

        $servers = [];
        foreach ($validated['server_url'] as $index => $name) {
            $servers[] = [
                'url' => $validated['server_url'][$index],
                'headers' => $validated['server_header'][$index] ?? null,
            ];
        }

        $channel->servers = $servers;
        $channel->save();

        return redirect()->route('channel.index')->with('success', 'channel created successfully.');
    }

    public function edit($id)
    {
        $channel = Channel::findOrFail($id);
        return view('channel.edit', compact('channel'));
    }

    public function update(Request $request, Channel $channel)
    {
        $validated = $request->validate([
            'channel_name' => 'required|string|max:255',
            'channel_logo' => 'nullable',
            'server_url.*' => 'required|url',
            'server_header.*' => 'nullable',
        ]);

        $channel->channel_name = $validated['channel_name'];

        if ($request->hasFile('channel_logo')) {
            $path = $request->file('channel_logo')->store('logos', 'public');
            $channel->channel_logo = env('APP_URL') . '/storage/' . $path;
        } else{
            $channel->channel_logo = $request->channel_logo;
        }

        $servers = [];
        foreach ($validated['server_url'] as $index => $name) {
            $servers[] = [
                'name' => $name,
                'url' => $validated['server_url'][$index],
                'headers' => $validated['server_header'][$index] ?? null,
            ];
        }

        $channel->servers = $servers;
        $channel->save();

        return redirect()->route('channel.index')->with('success', 'channel updated successfully.');
    }
}
