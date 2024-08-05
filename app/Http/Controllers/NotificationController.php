<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Services\FirebaseService;

class NotificationController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->middleware('auth');
        $this->firebaseService = $firebaseService;
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
        $response = $this->firebaseService->sendNotification(
            $request->title,
            $request->body,
            $request->img_url,
            $request->url
        );

        if ($response) {
            return redirect()
                ->back()
                ->with('success', 'Notification sent successfully.');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Error sending notification.');
        }
    }
}
