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
            'serverDetails' => [
                'url' => 'ur url',
                'mainUrl' => 'url',
                'privacyUrl' => 'url',
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
            'serverDetails' => [
                'key' => 'noti app key'
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

        $datas = AppSetting::find(1);
        $id = $datas->id;
        $serverDetails = $datas->serverDetails;
        $sponsorGoogle = $datas->sponsorGoogle;
        $sponsorText = $datas->sponsorText;
        $sponsorBanner = $datas->sponsorBanner;
        $sponsorInter = $datas->sponsorInter;

        return view('app-setting', compact('id', 'serverDetails', 'sponsorGoogle', 'sponsorText', 'sponsorBanner', 'sponsorInter'));
    }

    public function update(Request $request, $id)
    {
        $sponsorGoogle_status = $request->sponsorGoogle_status;
        $sponsorText_status = $request->sponsorText_status;
        $sponsorBanner_status = $request->sponsorBanner_status;
        $sponsorInter_status = $request->sponsorInter_status;

        $datas = AppSetting::find($id);

        $datas->serverDetails = [
            'url' => $request->url,
            'mainUrl' => $request->mainUrl,
            'privacyUrl' => $request->privacyUrl,
            'password' => $request->password,
            'password_image' => $request->password_image,
        ];

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
            'text' => $request -> text,
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

    private function status_check($data){
        if ($data === 'on') {
            return true;
        } else{
            return false;
        }
    }
}
