<?php

namespace App\Http\Controllers;

use App\Models\Building;

class AtlasVGController extends Controller
{

    public function demo()
    {
        Building::all()->each(function (Building $building) {
            var_dump($building->toArray());
        });
    }

    public function index()
    {
        $maps = [];
        foreach (['sample1.svg', 'sample2.svg'] as $i => $m) {
            $maps[$i + 1] = $this->getMap($m);
        }

        return view('main', [
            'maps' => $maps
        ]);
    }
}
