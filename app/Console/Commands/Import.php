<?php

namespace AtlasVG\Console\Commands;

use Illuminate\Support\Facades\DB;

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

            DB::beginTransaction();

            if (!is_readable($this->argument('file'))) {
                throw new \InvalidArgumentException('Unable to read file: ' . $this->argument('file'));
            }

            $buildings = json_decode(file_get_contents($this->argument('file')));
            if (json_last_error() !== 0) {
                throw new \Exception('Unable to decode json file due: ' . json_last_error_msg());
            }

            $this->info('Processing import file: ' . $this->argument('file'));

            $bar = $this->output->createProgressBar(count($buildings));
            $bar->start();

            foreach (\AtlasVG\Services\Import::data($buildings) as $building) {
                $bar->advance();
            }

            $this->info("\nDone.");

            DB::commit();

        } catch (\Exception $e) {

            $this->error($e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());

            DB::rollBack();
        }
    }
}