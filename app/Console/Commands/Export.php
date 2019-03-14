<?php

namespace AtlasVG\Console\Commands;

use AtlasVG\Helpers\DBData;

/**
 * Class Export
 * @package AtlasVG\Console\Commands
 */
class Export extends \Illuminate\Console\Command
{
    /**
     * The console command name.
     * @var string
     */
    protected $signature = "atlasvg:export {file}";

    /**
     * The console command description.
     * @var string
     */
    protected $description = "Export atlas information into a json file.";

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        try {

            $this->info('Exporting data into file: ' . $this->argument('file'));

            $bytes = file_put_contents(
                $this->argument('file'),
                json_encode(DBData::export(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );

            if (is_bool($bytes) || $bytes === 0) {
                throw new \Exception('Unable to save exported data, please check the export path.');
            }

            $this->info('Done!');

        } catch (\Exception $e) {
            $this->error($e->getMessage() . ' at ' . $e->getFile());
        }
    }
}