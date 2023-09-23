<?php

namespace App\Console\Commands;

use App\Models\HighLight;
use Illuminate\Console\Command;
use App\Http\Controllers\AutoMatches\AutoHighLightController;

class AutoHighLights extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-high-lights';

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
        $gg = new AutoHighLightController();
        $matches = $gg->getLiveMatches();
        $existingMatches = HighLight::where('is_auto_match', true)->get();

        $this->deleteMatches($existingMatches, $matches);

        foreach ($matches as $match) {
            // Check if a match with the same criteria exists
            $existingMatch = HighLight::where('match_time', $match['match_time'])
                ->where('home_team_name', $match['home_team_name'])
                ->where('away_team_name', $match['away_team_name'])
                ->first();

            if (!$existingMatch) {
                $match['servers'] = json_encode($match['servers']);

                $newMatch = new HighLight($match);
                $newMatch->save();
            } else {
                $existingServers = json_decode($existingMatch->servers, true);
                $incomingServers = $match['servers'];
                $newServers = [];

                foreach ($incomingServers as $incomingServer) {
                    $newServers[] = $incomingServer;
                }

                foreach ($existingServers as $existingServer) {
                    if ($existingServer['new'] === true) {
                        $newServers[] = $existingServer;
                    }
                }

                $existingMatch->servers = json_encode($newServers);
                $existingMatch->save();
            }
        }

        $this->info('Task Highlight completed successfully.');
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
