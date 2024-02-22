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
        // AppSetting::create([
        //     'serverDetails' => [
        //         'url' => 'https://raw.githubusercontent.com/devhtetmyat/git_server/main',
        //         'mainUrl' => 'https://app.fotliv.com',
        //         'privacyUrl' => 'https://fotliv.com',
        //     ],
        //     'sponsorGoogle' => [
        //         'status' => true,
        //         'android_banner' => 'ca-app-pub-4917606691315098/2487011314',
        //         'android_inter' => 'ca-app-pub-4917606691315098/6816288504',
        //         'android_appopen' => 'ca-app-pub-4917606691315098/6784498912',
        //         'ios_banner' => 'ca-app-pub-3940256099942544/2934735716',
        //         'ios_inter' => 'ca-app-pub-3940256099942544/4411468910',
        //         'ios_appopen' => 'ca-app-pub-3940256099942544/5662855259',
        //     ],
        //     'sponsorText' => [
        //         'status' => true,
        //         'text' => 'If you are facing with streaming Error, you can switch servers or contact to Admin > info.fotliv@gmail.com',
        //     ],
        //     'sponsorBanner' => [
        //         'status' => true,
        //         "smallAd" => "gg",
        //         "smallAdUrl" => "F",
        //         "mediumAd" => "FF",
        //         "mediumAdUrl" => "FF"
        //     ],

        //     'sponsorInter' => [
        //         'status' => True,
        //         'adImage' => 'https://blogger.googleusercontent.com/img/a/AVvXsEiKPpMPU46wIezJsp7CRyFuUe5Z1EHrS7B4nz7SWjvWMemSCAQ2OzuDns5JIxzDJ2nhecanpKj_NqR4U8iUNHhYY0EM3q8SBud6-S6ZeFox_o8AgK4819pYjME2g-w1RPUvKSAX2zPpWAN7Uwe0Qh5Hl92D7r4eQ9MbVg2bdS2P_6SgsVdxr7NLy-C_84B_',
        //         'adUrl' => 'https://fotliv.com'
        //     ],
        // ]);

        // AppSetting::create([
        //     'serverDetails' => [
        //         'key' => 'AAAAk2GC0RM:APA91bGhCaXt3YiCBT0tVaJV8w5qBRdz6uIf8iA17tFe5koug-okTkjOUWevWwK2qhSYcuPxi_bgfN45YTLjcmo0-F6so5QdLifQKOXFtvZdD14UFZlmEmAdfcdN5K4eWYUToTuuG4yU'
        //     ],
        //     'sponsorGoogle' => [
        //       null
        //     ],
        //     'sponsorText' => [
        //        null
        //     ],
        //     'sponsorBanner' => [
        //       null
        //     ],
        //     'sponsorInter' => [
        //        null
        //     ],
        // ]);

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
