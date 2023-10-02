<?php

namespace App\Http\Controllers\AutoMatches;

use GuzzleHttp\Client as GuzzleClient;
use Goutte\Client;
use Illuminate\Http\Request;
use voku\helper\HtmlDomParser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class AutoHighLightController extends Controller
{
   public function getLiveMatches()
    {
        $today = now();
        $allLiveMatches = [];
// l;
        for ($i = 0; $i < 5; $i++) {
            $date = $today->subDays($i);
            $dateStr = $date->format("Y-m-d");
            $baseUrl = "https://bscore.tv/highlight-data?is_hot=-1&timestamp=";
            $timestamp = strtotime($dateStr);
            $url = "{$baseUrl}{$timestamp}";
            $htmlContent = $this->fetchHtml($url);
            $crawler = new Crawler($htmlContent);
            $liveMatches = [];

            $crawler->filter('div.match-league-container')->each(function ($matchElement) use (&$liveMatches) {
                $anchorTag = $matchElement->filter('a');
                $matchUrl = $anchorTag->attr('href');

                try {
                    $homeTeamName = trim($anchorTag->filter('div.left-team div.txt-team-name')->text());
                    $homeTeamLogo = $anchorTag->filter('div.left-team img')->attr('data-src');

                    $awayTeamName = trim($anchorTag->filter('div.right-team div.txt-team-name')->text());
                    $awayTeamLogo = $anchorTag->filter('div.right-team img')->attr('data-src');

                    $matchTimeElement = $anchorTag->filter('div.time-match');
                    $isLive = $anchorTag->filter('div.txt-vs')->text();

                    if (strpos($isLive, 'isLive') !== false) {
                        $matchStatus = "Live";
                    } else {
                        $matchStatus = str_replace("\n", '', $isLive);
                    }

                    $teamScores = explode('-', $matchStatus);

                    if (count($teamScores) === 2) {
                        $home_team_score = trim($teamScores[0]);
                        $away_team_score = trim($teamScores[1]);
                    } else {
                        $home_team_score = 0;
                        $away_team_score = 0;
                    }

                    $matchTime = $matchTimeElement->attr('data-timestamp');
                    if (!$matchTime) {
                        $matchTime = $matchTimeElement->filter('span.txt_time')->attr('data-timestamp');
                    }

                    $matchTime = $matchTime ?: "0";

                    $leagueName = trim($matchElement->filter('h4.league-name')->text());

                    $matchDetails = [
                        "match_time" => $matchTime,
                        "home_team_name" => $homeTeamName,
                        "home_team_logo" => $homeTeamLogo,
                        "home_team_score" => $home_team_score,
                        "away_team_name" => $awayTeamName,
                        "away_team_logo" => $awayTeamLogo,
                        "away_team_score" => $away_team_score,
                        "league_name" => $leagueName,
                        "match_status" => 'Highlight',
                        "servers" => $this->getVideoDetails($matchUrl),
                        "is_auto_match" => true,
                    ];

                    $liveMatches[] = $matchDetails;
                } catch (Exception $e) {
                    // Handle exceptions if needed
                }
            });

            $allLiveMatches = array_merge($allLiveMatches, $liveMatches);
        }

        usort($allLiveMatches, function ($a, $b) {
            return strcmp($b['match_time'], $a['match_time']);
        });

        return $allLiveMatches;
    }

    function checkImage($url)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', $url);

        if ($response->getStatusCode() == 200) {
            return $url;
        } elseif ($response->getStatusCode() == 404) {
            return 'https://mtek3d.com/wp-content/uploads/2018/01/image-placeholder-500x500-300x300.jpg';
        } else {
            return 'https://mtek3d.com/wp-content/uploads/2018/01/image-placeholder-500x500-300x300.jpg';
        }
    }

    function getVideoDetails($matchUrl)
    {
        $htmlSource = $this->fetchHtml($matchUrl);
        $itemServers = $this->extractMp4Urls($htmlSource);
        $serverList = [];

        foreach ($itemServers as $i => $itemServer) {
            $serverUrl = $itemServer ?: 'https://static.videezy.com/system/resources/previews/000/047/490/original/200511-ComingSoon.mp4';

            $serverDetails = [
                'name' => "Server " . ($i + 1),
                'url' => $serverUrl,
                'referer' => 'https://live-streamfootball.com/',
                'new' => false,
            ];

            $serverList[] = $serverDetails;
        }

        return $serverList;
    }

private function getToken()
{
    $client = new GuzzleClient();

    $headers = [
        'Host' => 'bscore.tv',
        'Origin' => 'https://bscore.tv/',
        'Sec-Fetch-Site' => 'same-origin',
        'Referer' => 'https://bscore.tv/',
        'Content-Type' => 'text/plain',
    ];

    $data = [
        'referrer_link' => 'https://bscore.tv/',
        'first_link' => 'https://bscore.tv/',
    ];

    $response = $client->post('https://bscore.tv/me', [
        'headers' => $headers,
        'form_params' => $data,
    ]);

    $json_data = json_decode($response->getBody(), true);
    $token_livestream = $json_data['token_livestream'];

    return $token_livestream;
}

private function getStreamUrl($url)
{
    $client = new Client();

    $headers = [
        'Referer' => 'https://bscore.tv/',
    ];

    $params = [
        'm3u8' => $url,
        'token' => $this->getToken(),
        'is_vip' => 'true',
    ];

    $response = $client->get('https://live-streamfootball.com/index.php', [
        'headers' => $headers,
        'query' => $params,
    ]);

    $pattern = "/var\s+m3u8\s+=\s+'(https:\/\/[^']+)';/";

    preg_match($pattern, $response->getBody(), $matches);

    if (isset($matches[1])) {
        $m3u8_url = $matches[1];
        return $m3u8_url;
    } else {
        return 'M3U8 URL not found in the script tag.';
    }
}

  private function extractMp4Urls($htmlContent)
    {
        $pattern = '/(https?:\/\/[^\s"\'<>]+\.mp4)/';
        preg_match_all($pattern, $htmlContent, $mp4Urls);

        return $mp4Urls[0];
    }

    private function fetchHtml($url)
    {
        // $client = new Client();
        // $crawler = $client->request('GET', $url);

        // return $crawler->html();

        $response = file_get_contents($url);
        return $response;
    }
}
