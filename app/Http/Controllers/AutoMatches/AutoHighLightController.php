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
        $url = 'https://bingsport.com/en/high-light';
        $htmlContent = $this->fetchHtml($url);
        $dom = HtmlDomParser::str_get_html($htmlContent);
        $liveMatches = [];

        foreach ($dom->find('div.list-match-sport-live-stream') as $matchElement) {
            foreach ($matchElement->find('a') as $anchorTag) {
                $matchUrl = $anchorTag->href;

                try {
                    // Details for home team
                    $homeTeamName = trim($anchorTag->find('div.left-team div.txt-team-name', 0)->plaintext);
                    $homeTeamLogo = $anchorTag->find('div.left-team img', 0)->{'data-src'};

                    // Details for away team
                    $awayTeamName = trim($anchorTag->find('div.right-team div.txt-team-name', 0)->plaintext);
                    $awayTeamLogo = $anchorTag->find('div.right-team img', 0)->{'data-src'};

                    // Other info
                    $matchTimeElement = $anchorTag->find('div.time-match', 0);
                    $isLive = $anchorTag->find('div.txt-vs', 0)->plaintext;

                    $matchStatus = (strpos($isLive, 'isLive') !== false) ? 'Live' : str_replace("\n", '', $isLive);

                    $teamScores = explode('-', $matchStatus);
                    $homeTeamScore = (count($teamScores) === 2) ? trim($teamScores[0]) : 0;
                    $awayTeamScore = (count($teamScores) === 2) ? trim($teamScores[1]) : 0;

                    $matchTime = $matchTimeElement->getAttribute('data-timestamp');
                    $matchTime = $matchTime ?: $matchTimeElement->find('span.txt_time', 0)->getAttribute('data-timestamp');
                    $matchTime = $matchTime ?: '0';

                    $leagueName = trim($matchElement->find('div.league-name', 0)->plaintext);

                    $matchDetails = [
                        'match_time' => $matchTime,
                        'home_team_name' => $homeTeamName,
                        'home_team_logo' => $homeTeamLogo,
                        'home_team_score' => $homeTeamScore,
                        'away_team_name' => $awayTeamName,
                        'away_team_logo' => $awayTeamLogo,
                        'away_team_score' => $awayTeamScore,
                        'league_name' => $leagueName,
                        'match_status' => 'Highlight',
                        'servers' => $this->getVideoDetails($matchUrl),
                        'is_auto_match' => true,
                    ];

                    $liveMatches[] = $matchDetails;
                } catch (\Exception $e) {
                    // Handle exceptions if needed
                }
            }
        }

        return $liveMatches;
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
                'referer' => 'https://bingsport.com/',
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
            'Host' => 'bingsport.com',
            'Origin' => 'https://bingsport.com/',
            'Sec-Fetch-Site' => 'same-origin',
            'Referer' => 'https://bingsport.com/',
            'Content-Type' => 'text/plain',
        ];

        $data = [
            'referrer_link' => 'https://bingsport.com/',
            'first_link' => 'https://bingsport.com/',
        ];

        $response = $client->post('https://bingsport.com/me', [
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
            'Referer' => 'https://bingsport.com/',
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
