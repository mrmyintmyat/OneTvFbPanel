<?php

namespace App\Http\Controllers\AutoMatches;

use Illuminate\Http\Request;
use voku\helper\HtmlDomParser;
use App\Http\Controllers\Controller;

class AutoMatchesController extends Controller
{
    public function get_live_sports($url) {
        $html_content = $this->fetch_html($url);
        $dom = HtmlDomParser::str_get_html($html_content);
        $live_matches = array();

        foreach ($dom->find('div.list-match-sport-live-stream') as $match_element) {
            foreach ($match_element->find('a') as $anchor_tag) {
                $match_url = $anchor_tag->href;
                try {
                    $league_name = $anchor_tag->find('div.league-name', 0)->plaintext;
                    // return $league_name;
                    $match_details = $this->getDataFromMatches($match_url, $league_name);
                } catch (Exception $e) {
                    $league_name = '';
                    $match_details = $this->getDataFromMatches($match_url, $league_name);
                }

                if (
                    empty($match_details['home_team_name']) ||
                    empty($match_details['home_team_logo']) ||
                    empty($match_details['away_team_name']) ||
                    empty($match_details['away_team_logo'])
                ) {
                    continue;
                }
               $live_matches[] = $match_details;
            }
        }

        return $live_matches;
    }

    private function getDataFromMatches($match_url, $league_name) {
        $html_source = $this->fetch_html($match_url);
        $dom = HtmlDomParser::str_get_html($html_source);

        $current_time_seconds = time();
        $ten_minutes_before = $current_time_seconds + 600;
        $match_status = "";

        // Extracting team info and time
        // $og_name_tag = $dom->find('meta[property="og:title"]', 0);
        // $og_image_tag = $dom->find('meta[property="og:image"]', 0);
        // $og_image_url = $og_image_tag->content;
        $home_team_div = $dom->find('div.item-team.left', 0);
        $away_team_div = $dom->find('div.item-team:not(.left)', 0);
        $home_team_name = $home_team_div->find('img.lazy', 0)->alt;
        $home_team_logo = $home_team_div->find('img.lazy', 0)->{'data-src'};
        $away_team_name = $away_team_div->find('img.lazy', 0)->alt;
        $away_team_logo = $away_team_div->find('img.lazy', 0)->{'data-src'};

        $time_in_seconds = 0; // Initialize with a default value
        $gg = $dom->find('span[data-timestamp]');
        foreach ($gg as $span_element) {
            // return $span_element;
            $time_in_seconds = (int) $span_element->getAttribute('data-timestamp');
            break;
        }

        if ($current_time_seconds >= $time_in_seconds || $ten_minutes_before > $time_in_seconds) {
            $match_status = "Live";
        } else {
            $match_status = "Match";
        }

        // Extracting server list
        $item_servers = $dom->find('div.item-server');
        $server_list = array();

        foreach ($item_servers as $i => $item_server) {
            $data_link = $item_server->getAttribute('data-link');
            $parsed_url = parse_url($data_link);
            parse_str($parsed_url['query'], $query_params);
            $m3u8_value = $query_params['m3u8'];
            $server_url = $m3u8_value;

            if ($server_url == "" || $server_url == null) {
                $server_details = array(
                    'name' => 'Default Server',
                    'url' => 'https://static.videezy.com/system/resources/previews/000/047/490/original/200511-ComingSoon.mp4',
                    'referer' => 'https://fotliv.com/',
                    'new' => false,
                );
            } else {
                $server_details = array(
                    'name' => 'Server ' . ($i + 1),
                    'url' => $server_url,
                    'referer' => 'https://live-streamfootball.com/',
                    'new' => false,
                );
            }

            $server_list[] = $server_details;
        }

        $home_team_name = str_replace("\n", '', $home_team_name);
        $away_team_name = str_replace("\n", '', $away_team_name);

        $gmtOffset = $time_in_seconds + 23450;

        $adjustedTimestampMillis = $gmtOffset * 1000;
        // $timestampSeconds = $adjustedTimestampMillis / 1000;

        $data = array(
            "match_time" => strval($adjustedTimestampMillis),
            "home_team_name" => $home_team_name,
            "home_team_logo" => $this->checkLogo($home_team_logo),
            "away_team_name" => $away_team_name,
            "away_team_logo" => $this->checkLogo($away_team_logo),
            "league_name" => $league_name,
            "league_logo" => null,
            "match_status" => $match_status,
            "servers" => $server_list,
            "is_auto_match" => true
        );

        return $data;
    }

    private function fetch_html($url) {
        $response = file_get_contents($url);
        return $response;
    }

    private function checkLogo($url) {
        if ($url != "") {
            $response = get_headers($url);
            if (strpos($response[0], '200 OK') !== false) {
                return $url;
            } else {
                return 'https://origin-media.wedodemos.com/upload/images/nologo.png';
            }
        } else {
            return "";
        }
    }
}
