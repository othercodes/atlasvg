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
    protected $signature = "atlasvg:sync";

    /**
     * The console command description.
     * @var string
     */
    protected $description = "Synchronize info for all pointers.";

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        try {

            $result = \AtlasVG\Helpers\RemoteData::sync();
            $this->info("Successfully synchronized {$result['successful']}/{$result['total']} people.");
            $this->error("Failed to synchronize {$result['failed']}/{$result['total']} people. Check the logs for details.");

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}