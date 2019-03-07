<?php

namespace AtlasVG\Console\Commands;

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
    protected $signature = "import:atlas {file}";

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
        $this->info("Building hello!");

        
    }
}