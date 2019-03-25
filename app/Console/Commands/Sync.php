<?php

namespace AtlasVG\Console\Commands;

/**
 * Class Sync
 * @package AtlasVG\Console\Commands
 */
class Sync extends \Illuminate\Console\Command
{
    /**
     * The console command name.
     * @var string
     */
    protected $signature = "atlasvg:sync {bid}";

    /**
     * The console command description.
     * @var string
     */
    protected $description = "Synchronizes all pointers for a building.";

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        try {

            $result = \AtlasVG\Helpers\RemoteData::sync($this->argument('bid'));
            $total = $result['successful'] + $result['failed'];

            $this->info("Successfully synchronized {$result['successful']}/{$total} people.");

            if ($result['failed']) {
                $this->error("Failed to synchronize {$result['failed']}/{$total} people. Check the logs for details.");
            }

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}