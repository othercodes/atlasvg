<?php

namespace AtlasVG\Http\Controllers;

use AtlasVG\Models\Building;
use AtlasVG\Models\Level;

class AtlasVGController extends Controller
{

    public function demo()
    {
        Building::all()->each(function (Building $building) {
            var_dump($building->toArray());
            var_dump($building->levels->toArray());

            $building->levels->each(function (Level $level) {
                var_dump($level->building->toArray());
            });
        });
    }

    public function index()
    {

        $building = Building::select()->first();

        return view('main', [
            'building' => $building
        ]);
    }
}
