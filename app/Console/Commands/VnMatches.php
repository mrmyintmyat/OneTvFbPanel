<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AutoMatch;
use App\Models\VnMatch;
use App\Http\Controllers\AutoMatches\AutoVnMatchesController;

class VnMatches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-vnmatches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        set_time_limit(300);
        $gg = new AutoVnMatchesController();
        $matches = $gg->scrapeMatches();
        // return $matches;
        $existingMatches = VnMatch::where('is_auto_match', true)->get();

        $this->deleteMatches($existingMatches, $matches);

        foreach ($matches as $match) {
            // Check if a match with the same criteria exists
            $existingMatch = VnMatch::where('match_time', $match['match_time'])
                ->where('home_team_name', $match['home_team_name'])
                ->where('away_team_name', $match['away_team_name'])
                ->first();

            if (!$existingMatch) {
                $match['servers'] = json_encode($match['servers']);

                $newMatch = new VnMatch($match);
                $newMatch->save();
            } else {
                $existingServers = json_decode($existingMatch->servers, true);
                $incomingServers = $match['servers'];
                $newServers = [];

                foreach ($incomingServers as $incomingServer) {
                    $newServers[] = $incomingServer;
                }

                foreach ($existingServers as $existingServer) {
                    if (isset($existingServer['new']) && $existingServer['new'] === true) {
                        $newServers[] = $existingServer;
                    }
                }

                if ($match['match_status'] !== $existingMatch->match_status) {
                    $existingMatch->match_status = $match['match_status'];
                }

                if ($match['home_team_score'] !== $existingMatch->home_team_score) {
                    $existingMatch->home_team_score = $match['home_team_score'];
                }

                if ($match['away_team_score'] !== $existingMatch->away_team_score) {
                    $existingMatch->away_team_score = $match['away_team_score'];
                }

                $existingMatch->servers = json_encode($newServers);
                $existingMatch->update();
            }
        }

       $this->info('Task completed successfully.');
    }

    private function deleteMatches($existingMatches, $matches)
    {
        $apiMatchIdentifiers = [];

        foreach ($matches as $match) {
            $apiMatchIdentifiers[] = [
                'match_time' => $match['match_time'],
                'home_team_name' => $match['home_team_name'],
                'away_team_name' => $match['away_team_name'],
            ];
        }

        // Compare API matches with existing matches in the database
        foreach ($existingMatches as $existingMatch) {
            $existingMatchIdentifier = [
                'match_time' => $existingMatch->match_time,
                'home_team_name' => $existingMatch->home_team_name,
                'away_team_name' => $existingMatch->away_team_name,
            ];

            // Check if the existing match is not present in the API data
            if (!in_array($existingMatchIdentifier, $apiMatchIdentifiers, true)) {
                $existingMatch->delete();
            }
        }
    }
}
