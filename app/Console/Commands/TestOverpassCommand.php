<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OverpassService;

class TestOverpassCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'osm:test {type} {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test fetching data from Overpass API';

    /**
     * Execute the console command.
     */
    public function handle(OverpassService $overpassService)
    {
        $type = $this->argument('type');
        $id = (int) $this->argument('id');

        $this->info("Fetching data for {$type}/{$id}...");

        $data = $overpassService->fetchData($type, $id);

        if ($data) {
            $this->info("Data found:");
            $this->line(json_encode($data, JSON_PRETTY_PRINT));
        } else {
            $this->error("No data found or error occurred.");
        }
    }
}
