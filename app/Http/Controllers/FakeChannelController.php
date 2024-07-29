<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\FakeChannel;
use Illuminate\Http\Request;

class FakeChannelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $channels = FakeChannel::paginate(17);
        return view('fakechannel.index', compact('channels'));
    }

    public function create()
    {
        return view('fakechannel.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'channel_name' => 'required|string|max:255',
            'channel_logo' => 'required',
            'server_name.*' => 'required',
            'server_url.*' => 'required|url',
            'server_referer.*' => 'nullable',
            'server_type.*' => 'required',
        ]);

        $channel = new FakeChannel();
        $channel->channel_name = $validated['channel_name'];

        if ($request->hasFile('channel_logo')) {
            $path = $request->file('channel_logo')->store('logos', 'public');
            $channel->channel_logo = url('storage/' . $path);
        }else{
            $channel->channel_logo = $request->channel_logo;
        }

        $servers = [];
        foreach ($validated['server_url'] as $index => $name) {
            $servers[] = [
                'name' => $validated['server_name'][$index],
                'url' => $validated['server_url'][$index],
                'type' => $validated['server_type'][$index],
                'referer' => $validated['server_referer'][$index] ?? null,
            ];
        }

        $channel->servers = $servers;
        $channel->save();

        return redirect()->route('fakechannel.index')->with('success', 'channel created successfully.');
    }

    public function edit($id)
    {
        $channel = FakeChannel::findOrFail($id);
        return view('fakechannel.edit', compact('channel'));
    }

    public function update(Request $request, $id)
    {
        $channel = FakeChannel::findOrFail($id);

        $validated = $request->validate([
            'channel_name' => 'required|string|max:255',
            'channel_logo' => 'required',
            'server_name.*' => 'required',
            'server_url.*' => 'required|url',
            'server_referer.*' => 'required',
            'server_type.*' => 'required',
        ]);

        $channel->channel_name = $validated['channel_name'];

        if ($request->hasFile('channel_logo')) {
            $path = $request->file('channel_logo')->store('logos', 'public');
            $channel->channel_logo = url('storage/' . $path);
        } else{
            $channel->channel_logo = $request->channel_logo;
        }

        $servers = [];
        foreach ($validated['server_url'] as $index => $name) {
            $servers[] = [
                'name' => $validated['server_name'][$index],
                'url' => $validated['server_url'][$index],
                'type' => $validated['server_type'][$index],
                'referer' => $validated['server_referer'][$index] ?? null,
            ];
        }

        $channel->servers = $servers;
        $channel->save();

        return redirect()->back()->with('success', 'channel updated successfully.');
    }
}
