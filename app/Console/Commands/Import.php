<?php

namespace AtlasVG\Console\Commands;

use Doctrine\Common\Inflector\Inflector;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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

            $file = storage_path('app/' . $this->argument('file'));
            if (!is_readable($file)) {
                throw new \InvalidArgumentException('Unable to read/open file: ' . $file);
            }

            $data = json_decode(file_get_contents($file));
            if (json_last_error() !== 0) {
                throw new \Exception('Unable to decode json file due: ' . json_last_error_msg());
            }

            foreach ($data as $key => $value) {
                self::importFile($key, $value);
            }

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Process the import items to create the database models.
     * @param string $field
     * @param array|object $value
     */
    public static function importFile(string $field, $value): void
    {
        switch (gettype($value)) {
            case 'object':

                $model = '\AtlasVG\Models\\' . ucfirst(Inflector::singularize($field));

                /** @var Model $instance */
                $instance =  new $model(get_object_vars($value));
                $instance->save();

                break;
            case 'array':

                foreach ($value as $index => $item) {
                    self::importFile(is_int($index) ? $field : $index, $item);
                }

                break;
        }
    }
}