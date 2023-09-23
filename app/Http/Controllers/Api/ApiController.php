<?php

namespace App\Http\Controllers\Api;

use GuzzleHttp\Client;
use App\Models\HighLight;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use App\Models\FootballMatch;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{


    private function encryptAES($data, $encryptionKey) {
        try {
            $iv = openssl_random_pseudo_bytes(16);

            // Serialize the data array to a string
            $dataString = json_encode($data);

            $cipherText = openssl_encrypt($dataString, 'AES-256-CBC', $encryptionKey, 0, $iv);
            if ($cipherText === false) {
                return null; // Encryption failed
            }

            $encryptedData = base64_encode($iv . $cipherText);

            return json_encode(array("data" => $encryptedData));
        } catch (Exception $e) {
            echo $e->getMessage();
            return null;
        }
}

    public function matches(Request $request)
    {
        $count = $request->input('count', 10);
        $matches = FootballMatch::orderBy('match_time')
            ->take($count)
            ->get();
        // Iterate through matches and build a custom response
        $customResponse = [];
        foreach ($matches as $match) {
            $servers = json_decode($match->servers, true); // Decode the JSON "servers" attribute
            $serverDetails = [];

            // Iterate through servers and extract relevant details
            foreach ($servers as $server) {
                $serverDetails[] = [
                    'name' => $server['name'],
                    'url' => $server['url'],
                    'referer' => $server['referer'],
                ];
            }

            // Create a custom match entry without "match_status" but with "servers"
            $customMatch = [
                'id' => $match->id,
                'match_time' => $match->match_time,
                'home_team_name' => $match->home_team_name,
                'home_team_logo' => $match->home_team_logo,
                'home_team_score' => $match->home_team_score,
                'away_team_name' => $match->away_team_name,
                'away_team_logo' => $match->away_team_logo,
                'away_team_score' => $match->away_team_score,
                'league_name' => $match->league_name,
                'league_logo' => $match->league_logo,
                'servers' => $serverDetails,
            ];

            // Add the custom match entry to the response array
            $customResponse[] = $customMatch;
        }

        $datas = $this->encryptAES($customResponse, 'GG');
        return $datas;
    }

    public function app_setting()
    {
        $settings = AppSetting::all();
        $datas = $this->encryptAES($settings, 'GG');
        return $datas;
    }

    public function highlights(Request $request)
    {
        $count = $request->input('count', 10);
        $matches = HighLight::orderBy('match_time')
            ->take($count)
            ->get();
        // Iterate through matches and build a custom response
        $customResponse = [];
        foreach ($matches as $match) {
            $servers = json_decode($match->servers, true); // Decode the JSON "servers" attribute
            $serverDetails = [];

            // Iterate through servers and extract relevant details
            foreach ($servers as $server) {
                $serverDetails[] = [
                    'name' => $server['name'],
                    'url' => $server['url'],
                    'referer' => $server['referer'],
                ];
            }

            // Create a custom match entry without "match_status" but with "servers"
            $customMatch = [
                'id' => $match->id,
                'match_time' => $match->match_time,
                'home_team_name' => $match->home_team_name,
                'home_team_logo' => $match->home_team_logo,
                'home_team_score' => $match->home_team_score,
                'away_team_name' => $match->away_team_name,
                'away_team_logo' => $match->away_team_logo,
                'away_team_score' => $match->away_team_score,
                'league_name' => $match->league_name,
                'league_logo' => $match->league_logo,
                'servers' => $serverDetails,
            ];

            // Add the custom match entry to the response array
            $customResponse[] = $customMatch;
        }

        $datas = $this->encryptAES($customResponse, 'GG');
        return $datas;
    }
}
