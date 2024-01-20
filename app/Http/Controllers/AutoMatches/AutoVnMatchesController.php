<?php

namespace App\Http\Controllers\AutoMatches;

use RandomUserAgent;
use Illuminate\Support\Carbon;
use voku\helper\HtmlDomParser;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class AutoVnMatchesController extends Controller
{
    private $referer = 'https://www.channelemea.com/';

    public function scrapeMatches()
    {
        $user_agent = $this->getRandomUserAgent();
        $url = 'https://vebotv.ca/';
        $response = Http::withHeaders(['referer' => $this->referer, 'User-Agent' => $user_agent])->get($url);
        $htmlContent = $response->body();
        $liveMatches = $this->scrapeMatchesFromHtml($htmlContent);
        $jsonContent = json_encode($liveMatches, JSON_PRETTY_PRINT);
        return $liveMatches;

        // Specify the path where you want to save the encrypted JSON file
        // $outputFilePath = storage_path('app/vntvmatches.json');
        // file_put_contents($outputFilePath, $encryptedJson);

        // Log::info('Matches scraped and encrypted. JSON file saved to ' . $outputFilePath);
    }

    private function getRandomUserAgent()
    {
        // Your logic to get a random user agent, replace this with your implementation
        return 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3';
    }

    private function scrapeMatchesFromHtml($htmlContent)
    {
        // Use HtmlDomParser to parse HTML content
        $dom = HtmlDomParser::str_get_html($htmlContent);

        $currentDateTime = now();
        $allMatches = [];

        foreach ($dom->find('.match-main-option') as $matchItem) {
            // try {
                // Extract competition name
                $competitionName = $matchItem->find('.competition-label-option div[itemprop="name"]', 0)->plaintext;
                // return $matchItem;
                // Extract match details
                $matchLink = $matchItem->find('a.match-link', 0);
                $matchUrl = $matchLink->find('meta[itemprop="url"]', 0)->content;
                $matchStartDate = $matchLink->find('meta[itemprop="startDate"]', 0)->content;
                $matchStartDateTime = Carbon::parse($matchStartDate);

                $homeTeamScore = $awayTeamScore = '';

                try {
                    $scoreOpt = $matchItem->find('.match-score-option.match-score-live.owards', 0);
                    [$homeTeamScore, $awayTeamScore] = explode(' - ', $scoreOpt->plaintext);
                } catch (\Exception $e) {
                    // Handle score extraction exception
                }

                $matchStatus = $currentDateTime >= $matchStartDateTime || $currentDateTime->addMinutes(10) >= $matchStartDateTime ? 'Live' : 'Match';

                // Extract home and away teams
                $homeTeamName = $matchItem->find('.home-name.match-team div[itemprop="name"]', 0)->plaintext;
                $awayTeamName = $matchItem->find('.away-name.match-team div[itemprop="name"]', 0)->plaintext;
                $homeTeamLogo = $matchLink->find('.home-name img', 0)->src;
                $awayTeamLogo = $matchLink->find('.away-name img', 0)->src;

                $serverList = [];

                if ($matchStatus == 'Live') {
                    $user_agent = $this->getRandomUserAgent();
                    $response = Http::withHeaders(['referer' => $this->referer, 'User-Agent' => $user_agent])->get($matchUrl);
                    $htmlContent = $response->body();
                    $dom_server = HtmlDomParser::str_get_html($htmlContent);
                    $linksItem = $dom_server->find('.author-list a');
                    $serverUrlList = [];

                    foreach ($linksItem as $link) {
                        $link = $link->href;
                        $serverUrl = $this->getM3u8Url($link);

                        if ($serverUrl && $this->checkUrl($serverUrl, $this->referer)) {
                            $serverUrlList[] = $serverUrl;
                        }
                    }

                    foreach ($serverUrlList as $i => $finalServerUrl) {
                        $serverDetails = [
                            'name' => "Server $i",
                            'url' => $finalServerUrl,
                            'referer' => $this->referer,
                        ];
                        $serverList[] = $serverDetails;
                    }
                }

                $matchData = [
                    'match_time' => strval($matchStartDateTime->timestamp),
                    'home_team_name' => $homeTeamName,
                    'home_team_logo' => $this->checkLogo($homeTeamLogo),
                    'away_team_name' => $awayTeamName,
                    'away_team_logo' => $this->checkLogo($awayTeamLogo),
                    'league_name' => $competitionName,
                    'league_logo' => null,
                    'match_status' => $matchStatus,
                    'servers' => $serverList,
                    'is_auto_match' => true,
                ];

                if (count($serverList) > 0 || $matchStatus == 'Match') {
                    $allMatches[] = $matchData;
                } else {
                    Log::info('Bad');
                }
            // } catch (\Exception $exception) {
            //     Log::error('Error processing match item: ' . $exception->getMessage());
            // }
        }

        // Clear memory after parsing
        $dom->clear();

        return $allMatches;
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

    private function getM3u8Url($url)
    {
        $response = Http::withHeaders(['referer' => $this->referer, 'User-Agent' => ''])->get($url);
        $pattern = '/var\s+stream_link\s+=\s+"(https:\/\/[^"]+)"/';
        preg_match($pattern, $response->body(), $matches);

        return $matches[1] ?? '';
    }

    private function checkUrl($url, $referer)
    {
        try {
            $headers = $referer ? ['referer' => $referer, 'User-Agent' => ''] : [];
            $response = Http::withHeaders($headers)->get($url, ['timeout' => 5]);

            if ($response->successful()) {
                return true;
            } else {
                Log::info("Status Code: {$response->status()}");
                return false;
            }
        } catch (\Exception $e) {
            Log::info("An error occurred: {$e->getMessage()}");
            return false;
        }
    }
}
