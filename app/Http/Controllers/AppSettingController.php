<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AppSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // AppSetting::create([
        //     'appDetails' => [
        //         'app_name' => 'OneTvApp',
        //         'version' => '0.000',
        //         'share_message' => 'https://myintmyat.dev',
        //         'rate_url' => 'https://myintmyat.dev',
        //         'about_url' => 'https://myintmyat.dev',
        //         'privacy_url' => 'https://myintmyat.dev',
        //         'contact_us_url' => 'https://myintmyat.dev',
        //         'website_url' => 'https://myintmyat.dev',
        //     ],
        //     'sponsorGoogle' => [
        //         'status' => true,
        //         'android_banner' => 'barnyar',
        //         'android_inter' => 'barnyar',
        //         'android_appopen' => 'barnyar',
        //         'ios_banner' => 'barnyar',
        //         'ios_inter' => 'barnyar',
        //         'ios_appopen' => 'barnyar',
        //     ],
        //     'sponsorText' => [
        //         'status' => true,
        //         'text' => 'barnyar',
        //     ],
        //     'sponsorBanner' => [
        //         'status' => true,
        //     ],

        //     'sponsorInter' => [
        //         'status' => true,
        //         'data' => [
        //             [
        //                 'img_url' => 'https://yt3.ggpht.com/s5sSZJrP9SJ5qkGOTJCBFbfgz8IKnkB7mEAV0GaAWJiauzub0VPWFKFq2oDFzST_i06mMFe7qkA=s88-c-k-c0x00ffffff-no-rj',
        //                 'click_url' => 'https://zynn.gameswww',
        //                 'duration' => 5,
        //             ],
        //             [
        //                 'img_url' => 'http://localhost:9000/storage/images/YXKhBDm7i6TkJ58dFvkbuBvChqp0MfuDVbZwvnCd.png',
        //                 'click_url' => 'https://zynn.gamesrwrwr',
        //                 'duration' => 5,
        //             ],
        //         ],
        //     ],
        //     'updateInfo' => [
        //         'version_code' => 1,
        //         'force' => true,
        //         'title' => 'We found a latest version',
        //         'message' => 'Please update app',
        //         'update_url' => 'https://',
        //     ],
        // ]);

        // AppSetting::create([
        //     'appDetails' => [
        //         'key' => 'noti app key',
        //     ],
        //     'sponsorGoogle' => [null],
        //     'sponsorText' => [null],
        //     'sponsorBanner' => [null],
        //     'sponsorInter' => [null],
        //     'updateInfo' => [null],
        // ]);

        $datas = AppSetting::first();
        $id = $datas->id;
        $appDetails = $datas->appDetails;
        return view('settings.app-setting', compact('id', 'appDetails'));
    }

    public function update_setting()
    {
        $datas = AppSetting::first();
        $id = $datas->id;
        $updateInfos = $datas->updateInfo;
        return view('settings.update-setting', compact('id', 'updateInfos'));
    }

    public function ads_setting()
    {
        $settings = AppSetting::with('imageUrls')->first();
        $id = $settings->id;
        $sponsorGoogle = $settings->sponsorGoogle;
        $sponsorText = $settings->sponsorText;
        $sponsorBanner = $settings->sponsorBanner;
        $sponsorInter = $settings->sponsorInter;

        return view('settings.ads-setting', compact('id', 'sponsorGoogle', 'sponsorText', 'sponsorBanner', 'sponsorInter', 'settings'));
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

    public function updateInfo(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'updateInfos' => 'required|array',
            'updateInfos.version_code' => 'required|integer',
            'updateInfos.force' => 'required|boolean',
            'updateInfos.title' => 'required|string',
            'updateInfos.message' => 'required|string',
            'updateInfos.update_url' => 'required|url',
        ]);

        $appSetting = AppSetting::findOrFail($id);

        $appSetting->updateInfo = $validatedData['updateInfos'];

        $appSetting->save();

        return redirect()->back()->with('success', 'UpdateInfo updated successfully.');
    }

    public function update(Request $request, $id)
    {
        $sponsorGoogle_status = $request->sponsorGoogle_status;
        $sponsorText_status = $request->sponsorText_status;
        $sponsorBanner_status = $request->sponsorBanner_status;
        $sponsorInter_status = $request->sponsorInter_status;

        $datas = AppSetting::find($id);

        $imgInterUrls = $request->input('img_url_inter', []);
        $imgFiles = $request->file('img_url_inter', []);
        $clickUrls = $request->input('click_url_inter', []);
        $durations = $request->input('duration_inter', []);
        $click_urls_inter_file = $request->input('click_url_inter_file', []);
        $durations_inter_file = $request->input('duration_inter_file', []);

        // Update sponsorInter data
        $sponsorInterData = [];
        foreach ($imgInterUrls as $index => $imgUrl) {
            $sponsorInterData[] = [
                'img_url' => $imgUrl,
                'click_url' => $clickUrls[$index] ?? '',
                'duration' => $durations[$index] ?? '',
            ];
        }

        // Process file uploads
        foreach ($imgFiles as $index => $file) {
            if ($file && $file->isValid()) {
                $path = $file->store('images', 'public');
                $sponsorInterData[] = [
                    'img_url' => url(Storage::url($path)),
                    'click_url' => $click_urls_inter_file[$index],
                    'duration' => $durations_inter_file[$index] ?? '',
                ];
            }
        }

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
            'click_url' => $request->banner_click_url,
        ];

        $datas->sponsorInter = [
            'status' => $this->status_check($sponsorInter_status),
            'data' => $sponsorInterData,
        ];

        $datas->save();

        $imageDataArray = [];

        // Get all inputs including files
        $imgUrls = $request->input('img_url', []);
        $files = $request->file('img_url', []);
        $click_urls = $request->input('click_url', []);
        $click_urls_file = $request->input('click_url_file', []);

        // Process image URLs
        foreach ($imgUrls as $index => $url) {
            $imageDataArray[] = [
                'img_url' => $url,
                'click_url' => $click_urls[$index],
            ];
        }

        // Process file uploads
        foreach ($files as $index => $file) {
            if ($file && $file->isValid()) {
                $path = $file->store('images', 'public');
                $imageDataArray[] = [
                    'img_url' => url(Storage::url($path)),
                    'click_url' => $click_urls_file[$index],
                ];
            }
        }

        // Remove old image URLs
        $datas->imageUrls()->delete();

        // Store the new image data in the database
        foreach ($imageDataArray as $imageData) {
            $datas->imageUrls()->create($imageData);
        }

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
