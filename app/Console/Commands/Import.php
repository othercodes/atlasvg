<?php

namespace AtlasVG\Console\Commands;

use AtlasVG\Models\Building;
use AtlasVG\Models\Category;
use AtlasVG\Models\Level;
use AtlasVG\Models\Pointer;
use AtlasVG\Models\Space;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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

            $file = storage_path('app/' . $this->argument('file'));
            if (!is_readable($file)) {
                throw new \InvalidArgumentException('Unable to read/open file: ' . $file);
            }

            $buildings = json_decode(file_get_contents($file));
            if (json_last_error() !== 0) {
                throw new \Exception('Unable to decode json file due: ' . json_last_error_msg());
            }

            $this->info('Processing import file: ' . $file);

            foreach ($buildings as $buildingIndex => $building) {
                $this->info('> Importing building: ' . ($buildingIndex + 1));

                /** @var Building $buildingDBModel */
                $buildingDBModel = $this->saveModelOrNew($building, Building::class);

                if (isset($building->levels)) {
                    foreach ($building->levels as $levelIndex => $level) {
                        $this->info('-> Importing level: ' . ($levelIndex + 1));

                        /** @var Level $levelDBModel */
                        $levelDBModel = $this->saveModelOrNew($level, Level::class, [
                            $buildingDBModel
                        ]);

                        if (isset($level->pointers)) {
                            foreach ($level->pointers as $pointerIndex => $pointer) {
                                $this->info('-> Importing pointer: ' . ($pointerIndex + 1));

                                /** @var Category $categoryDBModel */
                                $categoryDBModel = isset($pointer->category_id)
                                    ? Category::find($pointer->category_id)
                                    : Category::find(1);

                                /** @var Space $spaceDBModel */
                                $spaceDBModel = isset($pointer->space_id)
                                    ? Space::find($pointer->space_id)
                                    : $levelDBModel->spaces()->first();

                                if (!isset($pointer->left) && !isset($pointer->top)) {
                                    $center = $levelDBModel->calculateRelativeSpaceCenter($spaceDBModel);

                                    $pointer->top = $center['y'];
                                    $pointer->left = $center['x'];
                                }

                                $pointerDBModel = $this->saveModelOrNew($pointer, Pointer::class, [
                                    $categoryDBModel,
                                    $spaceDBModel,
                                ]);

                            }
                        }
                    }
                }
            }

            $this->info("Done.");

            DB::commit();

        } catch (\Exception $e) {

            $this->error($e->getMessage() . ' at ' . $e->getFile());
            DB::rollBack();
        }
    }

    /**
     * Dynamic search to avoid duplicates
     * @param object $data
     * @param string $class
     * @param Model[] $parents
     * @return Model
     */
    private function saveModelOrNew(object $data, string $class, array $parents = []): Model
    {
        if (isset($data->id)) {

            /** @var Builder $class */
            $model = $class::find($data->id);
        } else {

            /** @var Builder $class */
            $query = $class::select();
            foreach ($data as $field => $value) {
                if (is_scalar($value) && !in_array($field, ['svg', 'surroundings'])) {
                    $query->where($field, '=', $value);
                }
            }

            /** @var Model $model */
            $model = $query->first();
        }

        if (!isset($model)) {
            /** @var Model $model */
            $model = new $class();
        }

        $model->fill(get_object_vars($data));

        foreach ($parents as $parent) {
            if (isset($parent)) {
                $relation = substr(strrchr(get_class($parent), "\\"), 1);
                $model->{strtolower($relation)}()
                    ->associate($parent);
            }
        }

        $model->save();

        if (method_exists($model, 'discover')) {
            call_user_func_array([$model, 'discover'], []);
        }

        return $model;
    }

}