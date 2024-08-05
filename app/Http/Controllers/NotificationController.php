<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use App\Models\FirebaseCredential;
use Illuminate\Support\Facades\Http;
use App\Http\Services\FirebaseService;

class NotificationController extends Controller
{
    protected $firebaseService;

    public function __construct()
    {
        // FirebaseService $firebaseService
        $this->middleware('auth');
        // $this->firebaseService = $firebaseService;
    }

    public function index()
    {
        FirebaseCredential::create([
            'type' => 'servicewrwe_account',
            'project_id' => 'on5etvr34343-a5353pp',
            'private_key_id' => 'f36b870d3fsfsf1361454dgdg9d32ec0eb9',
            'private_key' => '-----dgdgd40KG++CyizaFajrEMdZigmI1PalPcExCsosk3ANj5ZuY+sO\n6KcqEtnNHtRDvajGGFvrqNqernTCvIuxqFObnylhCFAj0/FdgdgH5NP9jBFUaSgyLDQnbwq\naJoVjq/DVU0m4l7wEw7svHwhEvy9akxhdGRtdpWENr1mdX2vklJefoyPUspr7Oxy\ngZgUoSnDRNdgd8j11v/jcPCKoQ=\n-----END PRIVATE KEY-----\n',
            'client_email' => 'firedgdgdnt.com',
            'client_id' => '104281egedgdg6890633ete51',
            'auth_uri' => 'https://accounts.tetoaueteth',
            'token_uri' => 'https://oauth2.googltetken',
            'auth_provider_x509_cert_url' => 'https://www.eeleapis.coeteh2/v1/certs',
            'client_x509_cert_url' => 'https://www.googete/v1/metadata/x509/firebase-adminsdk-7t8j8%40onetv-app.iam.gserviceaccount.com',
            'universe_domain' => 'googleapis.com',
        ]);

        $firebaseDatas = FirebaseCredential::first();

        // Exclude the unwanted attributes
        $excludedAttributes = ['id', 'updated_at', 'created_at'];
        $firebaseDatas = array_diff_key($firebaseDatas->getAttributes(), array_flip($excludedAttributes));

        $appsetting = AppSetting::where('sponsorGoogle', 'LIKE', '%null%')->first();
        $key = $appsetting->appDetails['key'];
        return view('notification.notification', compact('key', 'firebaseDatas'));
    }

    public function UpdateKey(Request $request)
    {
        $data = $request->all();

        $firebaseDatas = FirebaseCredential::first(); // Get the record to update

        foreach ($data as $key => $value) {
            if ($key !== '_token') {
                $firebaseDatas->$key = $value;
            }
        }

        $firebaseDatas->save();

        return redirect()->back()->with('success', 'Success Edit');
    }

    public function sendNotification(Request $request)
    {
        $response = $this->firebaseService->sendNotification($request->title, $request->body, $request->img_url, $request->url);

        if ($response) {
            return redirect()->back()->with('success', 'Notification sent successfully.');
        } else {
            return redirect()->back()->with('error', 'Error sending notification.');
        }
    }
}
