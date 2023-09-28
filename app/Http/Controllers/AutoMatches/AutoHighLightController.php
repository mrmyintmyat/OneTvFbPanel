<?php

namespace App\Http\Controllers\AutoMatches;

use Illuminate\Http\Request;
use voku\helper\HtmlDomParser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class AutoHighLightController extends Controller
{
    public function getLiveMatches()
    {
        $htmlContent = $this->fetchHtml('https://bingsport.com/high-light');
        $liveMatches = $this->parseHtml($htmlContent);

        return $liveMatches;
    }

    private function parseHtml($htmlContent)
    {
        $liveMatches = [];

        $dom = HtmlDomParser::str_get_html($htmlContent);

        foreach ($dom->find('div.list-match-sport-live-stream') as $matchElement) {
            foreach ($matchElement->find('a') as $anchorTag) {
                $matchUrl = $anchorTag->href;

                try {
                    // Details for home team
                    $homeTeamName = $anchorTag->find('div.left-team div.txt-team-name', 0)->plaintext;
                    $homeTeamLogo = $anchorTag->find('div.left-team img', 0)->getAttribute('data-src');

                    // Details for away team
                    $awayTeamName = $anchorTag->find('div.right-team div.txt-team-name', 0)->plaintext;
                    $awayTeamLogo = $anchorTag->find('div.right-team img', 0)->getAttribute('data-src');

                    // Other info
                    $matchTimeElement = $anchorTag->find('div.time-match', 0);

                    $scores = $anchorTag->find('div.txt-vs', 0)->plaintext;
                    $scoresArray = explode('-', $scores);

                    $home_team_score = trim($scoresArray[0]);
                    $away_team_score = trim($scoresArray[1]);

                    $matchTime = $matchTimeElement->getAttribute('data-timestamp');
                    if ($matchTime === null) {
                        $matchTime = $matchTimeElement->find('span.txt_time', 0)->getAttribute('data-timestamp');
                    }

                    if ($matchTime === null) {
                        $matchTime = "0";
                    }

                    // $gmtOffset = $matchTime + 23450;
                    // $adjustedTimestampMillis = $gmtOffset * 1000;

                    $leagueName = $anchorTag->find('div.league-name', 0)->plaintext;

                    $matchDetails = [
                        "match_time" => $matchTime,
                        "home_team_name" => trim($homeTeamName),
                        "home_team_logo" => $homeTeamLogo,
                        "home_team_score" => $home_team_score,
                        "away_team_name" => trim($awayTeamName),
                        "away_team_score" => $away_team_score,
                        "away_team_logo" => $awayTeamLogo,
                        "league_name" => $leagueName,
                        "match_status" => "HighLight",
                        "servers" => $this->getVideoDetails($matchUrl),
                        "is_auto_match" => true
                    ];

                    $liveMatches[] = $matchDetails;
                } catch (\Exception $e) {
                    // Handle any exceptions
                }
            }
        }

        return $liveMatches;
    }

    private function getVideoDetails($matchUrl)
{
    $htmlSource = $this->fetchHtml($matchUrl);
    $itemServers = $this->extractMp4Urls($htmlSource);

    $serverList = [];

    foreach ($itemServers as $i => $itemServer) {
        $serverUrl = $itemServer;

        if (empty($serverUrl)) {
            $serverDetails = [
                'name' => "Default Server",
                'url' => 'https://static.videezy.com/system/resources/previews/000/047/490/original/200511-ComingSoon.mp4',
                'referer' => 'https://fotliv.com/',
                'new' => false,
            ];
        } else {
            $serverDetails = [
                'name' => "Server" . ($i + 1),
                'url' => $serverUrl,
                'referer' => 'https://live-streamfootball.com/',
                'new' => false,
            ];
        }

        $serverList[] = $serverDetails;
    }

    return $serverList;
}

private function extractMp4Urls($htmlContent)
{
    $pattern = '/(https?:\/\/[^\s"\'<>]+\.mp4)/';
    preg_match_all($pattern, $htmlContent, $matches);

    return $matches[0];
}

private function fetchHtml($url)
{
    $response = Http::get($url);

    return $response->body();
}

}
