<?php

namespace AtlasVG\Console\Commands;

use Doctrine\Common\Inflector\Inflector;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class ShowItems
 * @package AtlasVG\Console\Commands
 */
class ShowItems extends \Illuminate\Console\Command
{
    /**
     * The console command name.
     * @var string
     */
    protected $signature = "atlasvg:show {item}";

    /**
     * The console command description.
     * @var string
     */
    protected $description = "Show the database entries for a given item type i.e: building or pointer.";

    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle()
    {
        try {

            $allowed = ['category', 'building', 'level', 'space', 'pointer'];
            $itemType = strtolower(Inflector::singularize($this->argument('item')));

            if (!in_array($itemType, $allowed)) {
                throw new \InvalidArgumentException('Invalid item type, must be one of ' . implode(', ', $allowed));
            }

            $model = 'AtlasVG\\Models\\' . ucfirst($itemType);
            if (class_exists($model)) {
                $headers = (new $model)->getVisible();
                $data = $model::all()->map(function (Model $model) {
                    return (new Collection($model->toArray()))
                        ->map(function ($attribute) {
                            if (is_array($attribute)) {
                                if (isset($attribute['id'])) {
                                    return $attribute['id'];
                                }
                                return count($attribute);
                            }
                            return substr($attribute, 0, 70);
                        })
                        ->toArray();
                });

                $this->table($headers, $data);

            } else {
                $this->error('Unable to load the required data model.');
            }

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}