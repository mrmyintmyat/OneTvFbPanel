<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;

class AppSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        AppSetting::create([
            'appDetails' => [
                'app_name' => 'OneTvApp',
                'version' => '0.000',
                'share_message' => 'https://myintmyat.dev',
                'rate_url' => 'https://myintmyat.dev',
                'about_url' => 'https://myintmyat.dev',
                'privacy_url' => 'https://myintmyat.dev',
                'contact_us_url' => 'https://myintmyat.dev',
                'website_url' => 'https://myintmyat.dev',
            ],
            'sponsorGoogle' => [
                'status' => true,
                'android_banner' => 'barnyar',
                'android_inter' => 'barnyar',
                'android_appopen' => 'barnyar',
                'ios_banner' => 'barnyar',
                'ios_inter' => 'barnyar',
                'ios_appopen' => 'barnyar',
            ],
            'sponsorText' => [
                'status' => true,
                'text' => 'barnyar',
            ],
            'sponsorBanner' => [
                'status' => true,
                "smallAd" => "barnyar",
                "smallAdUrl" => "barnyarF",
                "mediumAd" => "FbarnyarF",
                "mediumAdUrl" => "barnyar"
            ],

            'sponsorInter' => [
                'status' => True,
                'adImage' => 'barnyar',
                'adUrl' => 'barnyar'
            ],
        ]);

        AppSetting::create([
            'appDetails' => [
                'key' => 'noti app key',
            ],
            'sponsorGoogle' => [
              null
            ],
            'sponsorText' => [
               null
            ],
            'sponsorBanner' => [
              null
            ],
            'sponsorInter' => [
               null
            ],
        ]);

        $datas = AppSetting::first();
        $id = $datas->id;
        $appDetails = $datas->appDetails;
        return view('settings.app-setting', compact('id', 'appDetails'));
    }

    public function ads_setting()
    {
        $datas = AppSetting::first();
        $id = $datas->id;
        $sponsorGoogle = $datas->sponsorGoogle;
        $sponsorText = $datas->sponsorText;
        $sponsorBanner = $datas->sponsorBanner;
        $sponsorInter = $datas->sponsorInter;

        return view('settings.ads-setting', compact('id', 'sponsorGoogle', 'sponsorText', 'sponsorBanner', 'sponsorInter'));
    }

    public function updateAppSetting(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'appDetails' => 'required|array',
            'appDetails.app_name' => 'required|string',
            'appDetails.version' => 'required|string|max:10',
            'appDetails.share_message' => 'required|string',
            'appDetails.rate_url' => 'required|url',
            'appDetails.about_url' => 'required|url',
            'appDetails.privacy_url' => 'required|url',
            'appDetails.contact_us_url' => 'required|url',
            'appDetails.website_url' => 'required|url',
        ]);

        $appSetting = AppSetting::findOrFail($id);

        $appSetting->appDetails = $validatedData['appDetails'];

        $appSetting->save();

        return redirect()->back()->with('success', 'App settings updated successfully.');
    }

    public function update(Request $request, $id)
    {
        $sponsorGoogle_status = $request->sponsorGoogle_status;
        $sponsorText_status = $request->sponsorText_status;
        $sponsorBanner_status = $request->sponsorBanner_status;
        $sponsorInter_status = $request->sponsorInter_status;

        $datas = AppSetting::find($id);

        $datas->sponsorGoogle = [
            'status' => $this->status_check($sponsorGoogle_status),
            'android_banner' => $request->android_banner,
            'android_inter' => $request->android_inter,
            'android_appopen' => $request->android_appopen,
            'ios_banner' => $request->ios_banner,
            'ios_inter' => $request->ios_inter,
            'ios_appopen' => $request->ios_appopen,
        ];

        $datas->sponsorText = [
            'status' => $this->status_check($sponsorText_status),
            'text' => $request->text,
        ];

        $datas->sponsorBanner = [
            'status' => $this->status_check($sponsorBanner_status),
            'smallAd' => $request->banner_smallAd,
            'smallAdUrl' => $request->banner_smallAdUrl,
            'mediumAd' => $request->banner_mediumAd,
            'mediumAdUrl' => $request->banner_mediumAdUrl,
        ];

        $datas->sponsorInter = [
            'status' => $this->status_check($sponsorInter_status),
            'adImage' => $request->inter_adImage,
            'adUrl' => $request->inter_adUrl,
        ];

        $datas->save();

        return redirect()->back()->with('success', 'Update Success');
    }

    private function status_check($data)
    {
        if ($data === 'on') {
            return true;
        } else {
            return false;
        }
    }
}
