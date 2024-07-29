<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $appsetting = AppSetting::where('sponsorGoogle', 'LIKE', '%null%')->first();
        $key = $appsetting->appDetails['key'];
        return view('notification.notification', compact('key'));
    }

    public function UpdateKey(Request $request)
    {
        $appsetting = AppSetting::where('sponsorGoogle', 'LIKE', '%null%')->first();
        $appsetting->appDetails = [
            'key' => $request->key,
        ];

        $appsetting->save();

        return redirect()
            ->back()
            ->with('success', 'Success Edit');
    }

    public function sendNotification(Request $request)
    {
        $appsetting = AppSetting::where('sponsorGoogle', 'LIKE', '%null%')->first();
        $key = $appsetting->appDetails['key'];
        $notificationData = [
            'to' => '/topics/all_users',
            'notification' => [
                'title' => $request->title,
                'body' => $request->body,
                'image' => $request->img_url,
            ],
            'data' => [
                'url' => $request->url,
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $key,
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', $notificationData);

        if ($response->successful()) {
            return redirect()
            ->back()
            ->with('success', 'Success');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Error');
        }
    }
}
