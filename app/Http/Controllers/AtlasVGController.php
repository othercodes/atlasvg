<?php

namespace AtlasVG\Http\Controllers;

use AtlasVG\Models\Building;
use AtlasVG\Models\Category;

class AtlasVGController extends Controller
{
    public function index()
    {
        $building = Building::select()->first();
        $categories = Category::all();

        return view('main', [
            'building' => $building,
            'categories' => $categories,
        ]);
    }
}
