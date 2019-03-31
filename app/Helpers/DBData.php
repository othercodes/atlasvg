<?php

namespace AtlasVG\Helpers;

use AtlasVG\Models\Building;
use AtlasVG\Models\Category;
use AtlasVG\Models\Level;
use AtlasVG\Models\Pointer;
use AtlasVG\Models\Space;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Class Import
 * @package AtlasVG\Services
 */
class DBData
{
    /**
     * Return the export array (let the magic happens)
     * @return \Generator
     */
    public static function export(): \Generator
    {
        foreach (Building::all() as $building) {
            yield $building->toArray();
        }
    }

    /**
     * Import the data into the system
     * @param array $buildings
     * @return \Generator
     */
    public static function import(array $buildings): \Generator
    {
        Log::info("Incoming amount of buildings: " . count($buildings));
        foreach ($buildings as $buildingIndex => $building) {

            Log::info("Processing building index {$buildingIndex}", [
                'building' => $buildings
            ]);

            /** @var Building $buildingDBModel */
            $buildingDBModel = self::saveModelOrNew($building, Building::class);

            if (isset($building['levels'])) {

                Log::info("Amount of levels for building {$buildingIndex}: " . count($building['levels']));
                foreach ($building['levels'] as $levelIndex => $level) {

                    Log::info("Processing level index {$levelIndex}", [
                        'level' => $level
                    ]);

                    /** @var Level $levelDBModel */
                    $levelDBModel = self::saveModelOrNew($level, Level::class, [
                        $buildingDBModel
                    ]);

                    if (isset($level['pointers'])) {

                        Log::info("Amount of pointers for level {$levelIndex}: " . count($level['pointers']));
                        foreach ($level['pointers'] as $pointerIndex => $pointer) {

                            Log::info("Processing pointer index {$pointerIndex}", [
                                'pointer' => $pointer
                            ]);

                            /** @var Category $categoryDBModel */
                            $categoryDBModel = self::saveModelOrNew(
                                isset($pointer['category'])
                                    ? $pointer['category']
                                    : ['id' => 1],
                                Category::class
                            );

                            /** @var Space $spaceDBModel */
                            $spaceDBModel = isset($pointer['room'])
                                ? Space::where('level_id', '=', $levelDBModel->id)
                                    ->where('data', '=', $pointer['room'])
                                    ->first()
                                : $levelDBModel->spaces()->first();

                            if (!$spaceDBModel) {
                                Log::info("Unable to find valid space ({$pointer['room']}) for pointer index: {$pointerIndex}");
                                continue;
                            }

                            if (!isset($pointer['left']) && !isset($pointer['top'])) {
                                $center = $levelDBModel->calculateRelativeSpaceCenter($spaceDBModel);
                                $pointer['top'] = $center['y'];
                                $pointer['left'] = $center['x'];
                            }

                            self::saveModelOrNew($pointer, Pointer::class, [
                                $categoryDBModel,
                                $spaceDBModel,
                            ]);

                        }
                    }
                }
            }

            Log::info("Import building index {$buildingIndex} done!");

            yield $buildingDBModel;
        }
    }

    /**
     * Dynamic search to avoid duplicates
     * @param array $data
     * @param string $class
     * @param Model[] $parents
     * @return Model
     */
    private static function saveModelOrNew(array $data, string $class, array $parents = []): Model
    {
        if (isset($data['id'])) {

            Log::info("Trying to load {$class} by id {$data['id']} for update operation.");

            /** @var Builder $class */
            $model = $class::find($data['id']);

        } else {

            Log::info("Trying to load {$class} by params for update operation.");

            /** @var Builder $class */
            $query = $class::select();
            foreach ($data as $field => $value) {
                if (is_scalar($value) && !in_array($field, ['svg', 'map', 'space', 'room'])) {
                    $query->where($field, '=', $value);
                }
            }

            /** @var Model[] $parent */
            foreach ($parents as $parent) {
                $relation = strtolower(substr(strrchr(get_class($parent), "\\"), 1));
                $query->where($relation . '_id', '=', $parent->id);
            }

            Log::debug("Search query by params: {$query->toSql()}.");

            /** @var Model $model */
            $model = $query->first();
        }

        if (!isset($model)) {
            /** @var Model $model */
            $model = new $class();
            Log::info("Item {$class} not found id database, creating new item {$class}.");
        } else {
            Log::info("Item {$class} found, performing 'update' operation.");
        }

        $model->fill($data);

        if (count($parents) > 0) {
            Log::info("Processing parents of {$class}.");

            foreach ($parents as $parent) {

                $relation = substr(strrchr(get_class($parent), "\\"), 1);
                Log::info("Associating {$class} with {$relation}.");

                $model->{strtolower($relation)}()->associate($parent);
            }
        }

        $model->save();

        Log::info("Saved item {$class}.", [
            'model' => $model->toJson()
        ]);

        if (method_exists($model, 'discover')) {
            Log::info("Executing discover() over item {$class}.");
            call_user_func_array([$model, 'discover'], []);
        }

        return $model;
    }
}