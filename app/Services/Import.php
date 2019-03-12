<?php

namespace AtlasVG\Services;

use AtlasVG\Models\Building;
use AtlasVG\Models\Category;
use AtlasVG\Models\Level;
use AtlasVG\Models\Pointer;
use AtlasVG\Models\Space;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Import
 * @package AtlasVG\Services
 */
class Import
{
    /**
     * Import the data into the system
     * @param array $buildings
     * @return \Generator
     */
    public static function data(array $buildings)
    {
        foreach ($buildings as $buildingIndex => $building) {

            /** @var Building $buildingDBModel */
            $buildingDBModel = self::saveModelOrNew($building, Building::class);

            if (isset($building->levels)) {
                foreach ($building->levels as $levelIndex => $level) {

                    /** @var Level $levelDBModel */
                    $levelDBModel = self::saveModelOrNew($level, Level::class, [
                        $buildingDBModel
                    ]);

                    if (isset($level->pointers)) {
                        foreach ($level->pointers as $pointerIndex => $pointer) {

                            /** @var Category $categoryDBModel */
                            $categoryDBModel = self::saveModelOrNew(
                                isset($pointer->category)
                                    ? $pointer->category
                                    : (object)['id' => 1],
                                Category::class
                            );

                            /** @var Space $spaceDBModel */
                            $spaceDBModel = isset($pointer->space)
                                ? Space::where('level_id', '=', $levelDBModel->id)
                                    ->where('data', '=', $pointer->space)
                                    ->first()
                                : $levelDBModel->spaces()->first();

                            if (!$spaceDBModel) {
                                continue;
                            }

                            if (!isset($pointer->left) && !isset($pointer->top)) {
                                $center = $levelDBModel->calculateRelativeSpaceCenter($spaceDBModel);

                                $pointer->top = $center['y'];
                                $pointer->left = $center['x'];
                            }

                            $pointerDBModel = self::saveModelOrNew($pointer, Pointer::class, [
                                $categoryDBModel,
                                $spaceDBModel,
                            ]);

                        }
                    }
                }
            }

            yield $buildingDBModel;
        }
    }

    /**
     * Dynamic search to avoid duplicates
     * @param object|int $data
     * @param string $class
     * @param Model[] $parents
     * @return Model
     */
    private static function saveModelOrNew(object $data, string $class, array $parents = []): Model
    {
        if (isset($data->id)) {

            /** @var Builder $class */
            $model = $class::find($data->id);
        } else {

            /** @var Builder $class */
            $query = $class::select();
            foreach ($data as $field => $value) {
                if (is_scalar($value) && !in_array($field, ['svg', 'surroundings', 'space'])) {
                    $query->where($field, '=', $value);
                }
            }

            /** @var Model[] $parent */
            foreach ($parents as $parent) {
                $relation = strtolower(substr(strrchr(get_class($parent), "\\"), 1));
                $query->where($relation . '_id', '=', $parent->id);
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
            $relation = substr(strrchr(get_class($parent), "\\"), 1);
            $model->{strtolower($relation)}()
                ->associate($parent);
        }

        $model->save();

        if (method_exists($model, 'discover')) {
            call_user_func_array([$model, 'discover'], []);
        }

        return $model;
    }
}