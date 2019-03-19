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
    protected $signature = "atlasvg:sync {building_id}";

    /**
     * The console command description.
     * @var string
     */
    protected $description = "Synchronize all pointers for {building_id}.";

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        try {

            $id = $this->argument('building_id');
            $result = \AtlasVG\Helpers\RemoteData::sync($id);
            $total = $result['successful'] + $result['failed'];

            $this->info("Successfully synchronized {$result['successful']}/{$total} people.");
            $this->error("Failed to synchronize {$result['failed']}/{$total} people. Check the logs for details.");

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}