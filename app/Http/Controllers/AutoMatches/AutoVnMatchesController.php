<?php

namespace App\Http\Controllers\AutoMatches;

use RandomUserAgent;
use App\Models\League;
use Illuminate\Support\Carbon;
use voku\helper\HtmlDomParser;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class AutoVnMatchesController extends Controller
{
    public function scrapeMatches()
    {
        $url = 'https://raw.githubusercontent.com/devmm12/data/main/soccer.json';
        $response = Http::get($url);
        $htmlContent = $response->body();
        $matches = $this->decryptAES($htmlContent);
        // return $matches;
        $matchData = [];
        foreach ($matches as $match) {
            $serverList = [];
            if ($match['match_status'] === 'Live') {
                if (!empty($match['servers'])) {
                    foreach ($match['servers'] as $i => $finalServerUrl) {
                        $serverDetails = [
                            'name' => "Server " . ($i + 1),
                            'url' => $finalServerUrl['link'],
                            'referer' => $finalServerUrl['referer'],
                            'new' => false,
                        ];
                        $serverList[] = $serverDetails;
                    }
                } else {
                    $match['match_status'] = 'vs';
                }
            }
            $homeTeamScore = isset($match['homeTeamScore']) ? $match['homeTeamScore'] : null;
            $awayTeamScore = isset($match['awayTeamScore']) ? $match['awayTeamScore'] : null;
            $League = League::where('name', $match['league_name'])->first();

            if (!$League) {
                $League = League::create([
                    'name' => $match['league_name'],
                    'logo' => $match['league_logo'],
                ]);
            }
            $matchData[] = [
                'match_time' => strval($match['match_time']),
                'home_team_name' => $match['home_team_name'],
                'home_team_score' => $homeTeamScore,
                'home_team_logo' => $match['home_team_logo'],
                'away_team_name' => $match['away_team_name'],
                'away_team_logo' => $match['away_team_logo'],
                'away_team_score' => $awayTeamScore,
                'league_id' => $League->id,
                'match_status' => $match['match_status'],
                'servers' => $serverList,
                'is_auto_match' => true,
            ];
        }

        // Convert the match data array to JSON format
        $jsonMatches = json_encode($matchData);

        return $matchData;
    }

    private function decryptAES($encryptedData)
    {
        try {
            $key = 'ht3tMyatAuNg1234';

            // Decode the JSON string
            $data = json_decode($encryptedData, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON data');
            }

            // Extract IV and encrypted data from the JSON array
            $iv = base64_decode(key($data));
            $encryptedData = base64_decode(current($data));

            // Perform decryption
            $decryptedData = openssl_decrypt($encryptedData, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv);

            if ($decryptedData === false) {
                throw new \Exception('Decryption failed');
            }

            // Decode the decrypted data from JSON
            $decryptedData = json_decode($decryptedData, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON data after decryption');
            }

            return $decryptedData;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }
    private function checkLogo($url)
    {
        if ($url != '') {
            $response = Http::get($url);

            if ($response->successful()) {
                return $url;
            } else {
                return 'https://origin-media.wedodemos.com/upload/images/nologo.png';
            }
        } else {
            return '';
        }
    }
}
