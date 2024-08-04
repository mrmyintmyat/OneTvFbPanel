<?php

namespace App\Http\Controllers\Api;

use GuzzleHttp\Client;
use App\Models\Channel;
use App\Models\VnMatch;
use App\Models\HighLight;
use App\Models\AppSetting;
use App\Models\FakeChannel;
use Illuminate\Http\Request;
use App\Models\FootballMatch;
use App\Models\SliderSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AutoMatches\AutoVnMatchesController;

class ApiController extends Controller
{
    private function encryptAES($data, $encryptionKey)
    {
        try {
            $key = env('ENCRYPTION_KEY');
            $iv = openssl_random_pseudo_bytes(16);
            $dataAsString = json_encode($data);
            // $data = json_decode($data, true);
            $padded_data = openssl_encrypt($dataAsString, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv);

            if ($padded_data === false) {
                throw new Exception('Encryption failed');
            }

            $iv_base64 = base64_encode($iv);
            $encrypted_data_base64 = base64_encode($padded_data);
            // return $encrypted_data_base64;
            return json_encode([
                $iv_base64 => $encrypted_data_base64,
            ]);
        } catch (Exception $e) {
            echo $e->getMessage();
            return null;
        }
    }

    public function matches(Request $request)
    {
        // $count = $request->input('count', 10);
        if ($request->input('all') == true) {
            $matches = VnMatch::orderBy('match_time')
                ->get()
                ->makeHidden(['created_at', 'updated_at']);
        } else {
            $matches = VnMatch::orderBy('match_time')
                ->paginate(10)
                ->makeHidden(['created_at', 'updated_at']);
        }
        // return $matches;
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
                    'referer' => $server['referer'] ?? '',
                    'type' => $server['type'],
                ];
            }

            $customMatch = [
                'id' => $match->id,
                'match_time' => $match->match_time,
                'match_status' => $match->match_status,
                'home_team_name' => $match->home_team_name,
                'home_team_logo' => $match->home_team_logo,
                'home_team_score' => $match->home_team_score !== null ? (string) $match->home_team_score : '',
                'away_team_name' => $match->away_team_name,
                'away_team_logo' => $match->away_team_logo,
                'away_team_score' => $match->away_team_score !== null ? (string) $match->away_team_score : '',
                'league_name' => $match->league->name,
                'league_logo' => $match->league->logo,
                'servers' => $serverDetails,
            ];

            // Add the custom match entry to the response array
            $customResponse[] = $customMatch;
        }
        $datas = $this->encryptAES($customResponse, 'GG');
        return $datas;
    }

    public function vn_matches(Request $request)
    {
        set_time_limit(300);
        $gg = new AutoVnMatchesController();
        $matches = $gg->scrapeMatches();
        // $matches = json_decode($gg->scrapeMatches(), true);
        // return $gg->scrapeMatches();
        // Iterate through matches and build a custom response
        // $customResponse = [];
        // foreach ($matches as $match) {
        //     $serverDetails = [];
        //     $servers = $match['servers']; // Access the "servers" array directly

        //     // Iterate through servers and extract relevant details
        //     foreach ($servers as $server) {
        //         $serverDetails[] = [
        //             'name' => $server['name'],
        //             'url' => $server['url'],
        //             'referer' => $server['referer'],
        //         ];
        //     }

        //     $customMatch = [
        //         'match_time' => $match['match_time'],
        //         'home_team_name' => $match['home_team_name'],
        //         'home_team_logo' => $match['home_team_logo'],
        //         'home_team_score' => isset($match['home_team_score']) ? (string) $match['home_team_score'] : '',
        //         'away_team_name' => $match['away_team_name'],
        //         'away_team_logo' => $match['away_team_logo'],
        //         'away_team_score' => isset($match['away_team_score']) ? (string) $match['away_team_score'] : '',
        //         'league_name' => $match['league_name'],
        //         'league_logo' => $match['league_logo'],
        //         'match_status' => $match['match_status'],
        //         'servers' => $serverDetails,
        //     ];

        //     // Add the custom match entry to the response array
        //     $customResponse[] = $customMatch;
        // }

        // // Encode the custom response array as JSON
        // $jsonResponse = $customResponse;

        // $datas = $this->encryptAES($jsonResponse, 'GG');
        return $matches;
    }

    public function app_setting(Request $request)
    {
        // Check if the query parameter 'v' is set to 2
        if ($request->query('v') === '2') {
            $settings = AppSetting::with('imageUrls')->select('serverDetails', 'sponsorGoogle', 'sponsorText', 'sponsorBanner', 'sponsorInter')->first();
        } else {
            $settings = AppSetting::with('imageUrls')->first();

            $settingsArray = $settings->toArray();

            // Remove sensitive fields
            $settingsArray = $settings->makeHidden(['created_at', 'updated_at'])->toArray();
            unset($settingsArray['serverDetails']['password']);
            unset($settingsArray['serverDetails']['password_image']);
        }

        $imageUrlsData = $settings->imageUrls->map(function ($imageUrl) {
            return [
                'img_url' => $imageUrl->img_url ?? '',
                'click_url' => $imageUrl->click_url ?? '',
            ];
        });
        // Encrypt the data
        $response = [
            'appDetails' => $settings->appDetails,
            'sponsorGoogle' => $settings->sponsorGoogle,
            'sponsorText' => $settings->sponsorText,
            'sponsorBanner' => [
                'status' => $settings->sponsorBanner['status'],
                'data' => $imageUrlsData,
            ],
            'sponsorInter' => $settings->sponsorInter,
            'updateInfo' => $settings->updateInfo,
        ];

        $encryptedData = $this->encryptAES($response, 'woww');

        return $response;
    }

    public function slider_setting(Request $request)
    {
        // Fetch the first slider setting with related image URLs
        $settings = SliderSetting::with('imageUrls')->first();
        if ($settings) {
            $settings->makeHidden(['created_at', 'updated_at']);

            // Hide the attributes for the related models if necessary
            if ($settings->relationLoaded('imageUrls')) {
                $settings->imageUrls->each->makeHidden(['created_at', 'updated_at']);
            }
        }
        // Transform the data into the desired format
        $imageUrlsData = $settings->imageUrls->map(function ($imageUrl) {
            return [
                'img_url' => $imageUrl->img_url ?? '',
                'click_url' => $imageUrl->click_url ?? '',
            ];
        });
        $transformedData = [
            'status' => (bool) $settings->status,
            'autoplay' => (bool) $settings->autoplay,
            'duration' => (string) $settings->duration,
            'data' => $imageUrlsData,
        ];

        // Encrypt the data if necessary
        $encryptedData = $this->encryptAES($transformedData, 'woww');

        // Return the transformed data
        return $encryptedData;
    }

    public function channels(Request $request)
    {
        $channels = Channel::all()->makeHidden(['created_at', 'updated_at']);

        // Recursive function to handle null values
        $replaceNullWithEmptyString = function ($item) use (&$replaceNullWithEmptyString) {
            if (is_array($item) || is_object($item)) {
                return collect($item)
                    ->map(function ($value) use ($replaceNullWithEmptyString) {
                        return $replaceNullWithEmptyString($value);
                    })
                    ->toArray();
            }
            return $item === null ? '' : $item;
        };

        $channels = $channels->map(function ($channel) use ($replaceNullWithEmptyString) {
            return $replaceNullWithEmptyString($channel);
        });

        $encryptedData = $this->encryptAES($channels, 'woww');

        return $encryptedData;
    }

    public function fakechannels(Request $request)
    {
        $channels = FakeChannel::all()->makeHidden(['created_at', 'updated_at']);

        // Recursive function to handle null values
        $replaceNullWithEmptyString = function ($item) use (&$replaceNullWithEmptyString) {
            if (is_array($item) || is_object($item)) {
                return collect($item)
                    ->map(function ($value) use ($replaceNullWithEmptyString) {
                        return $replaceNullWithEmptyString($value);
                    })
                    ->toArray();
            }
            return $item === null ? '' : $item;
        };

        $channels = $channels->map(function ($channel) use ($replaceNullWithEmptyString) {
            return $replaceNullWithEmptyString($channel);
        });

        $encryptedData = $this->encryptAES($channels, 'woww');

        return $encryptedData;
    }

    public function highlights(Request $request)
    {
        if ($request->input('all') == true) {
            $matches = HighLight::orderBy('match_time')
                ->get()
                ->makeHidden(['created_at', 'updated_at']);
        } else {
            $matches = HighLight::orderBy('match_time')
                ->paginate(10)
                ->makeHidden(['created_at', 'updated_at']);
        }
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
                    'referer' => $server['referer'] ?? '',
                    'type' => $server['type'],
                ];
            }

            // Create a custom match entry without "match_status" but with "servers"
            $customMatch = [
                'id' => $match->id,
                'match_time' => $match->match_time,
                'match_status' => 'highlights',
                'home_team_name' => $match->home_team_name,
                'home_team_logo' => $match->home_team_logo,
                'home_team_score' => $match->home_team_score !== null ? (string) $match->home_team_score : '',
                'away_team_name' => $match->away_team_name,
                'away_team_logo' => $match->away_team_logo,
                'away_team_score' => $match->away_team_score !== null ? (string) $match->away_team_score : '',
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

    public function fetchLeagues()
    {
        // Create a new Guzzle HTTP client instance
        $client = new Client();

        // List of league IDs
        $leagueIds = [47, 87, 54, 55];
        $leagueNames = ['Premier league', 'Laliga', 'Bundesliga', 'Series A'];
        // Array to store league data
        $allLeaguesData = [];

        // Fetch data for each league
        foreach ($leagueIds as $count => $leagueId) {
            $response = $client->get("https://www.fotmob.com/api/leagues?id={$leagueId}");
            $data = json_decode($response->getBody(), true);

            $leagueName = $leagueNames[$count];
            $teamsData = $data['table'][0]['data']['table']['all'];
            $teams = [];

            foreach ($teamsData as $i => $team) {
                $id = $team['id'];
                $name = $team['name'];
                $logo = "https://images.fotmob.com/image_resources/logo/teamlogo/{$id}.png";
                $position = $i + 1;
                $points = $team['pts'];
                $played = $team['played'];
                $wins = $team['wins'];
                $draws = $team['draws'];
                $losses = $team['losses'];

                $allLeaguesData[] = [
                    'league_name' => $leagueName,
                    'name' => $name,
                    'logo' => $logo,
                    'position' => $position,
                    'points' => $points,
                    'played' => $played,
                    'wins' => $wins,
                    'draws' => $draws,
                    'losses' => $losses,
                ];
            }
        }

        // Return the league data
        $datas = $this->encryptAES($allLeaguesData, 'GG');
        return $datas;
    }
}
