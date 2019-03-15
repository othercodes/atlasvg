<?php

namespace AtlasVG\Console\Commands;

use AtlasVG\Models\Building;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class Import
 * @package AtlasVG\Console\Commands
 */
class Import extends \Illuminate\Console\Command
{
    /**
     * The console command name.
     * @var string
     */
    protected $signature = "atlasvg:import {file}";

    /**
     * The console command description.
     * @var string
     */
    protected $description = "Import atlas information using a json file.";

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        try {

            if (!is_readable($this->argument('file'))) {
                throw new \InvalidArgumentException('Unable to read file: ' . $this->argument('file'));
            }

            $buildings = json_decode(file_get_contents($this->argument('file')), true);
            if (json_last_error() !== 0) {
                throw new \Exception('Unable to decode json file due: ' . json_last_error_msg());
            }

            $this->info('Processing import file: ' . $this->argument('file'));

            $bar = $this->output->createProgressBar(count($buildings));
            $bar->start();

            DB::beginTransaction();

            /** @var Building $building */
            foreach (\AtlasVG\Helpers\DBData::import($buildings) as $building) {
                Log::debug("Building ID{$building->id} {$building->name} imported");
                $bar->advance();
            }

            $bar->finish();

            DB::commit();

            $this->info("\nDone.");

        } catch (\Exception $e) {

            $this->error('Unable to perform import task due: ' . $e->getMessage());
            Log::error('Import task fail due: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());

            DB::rollBack();
        }
    }
}