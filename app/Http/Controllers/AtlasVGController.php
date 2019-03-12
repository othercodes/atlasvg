<?php

namespace AtlasVG\Http\Controllers;

use AtlasVG\Models\Building;
use AtlasVG\Models\Category;

class AtlasVGController extends Controller
{
    /**
     * Main index view
     * @param int $bid
     * @return \Illuminate\View\View
     */
    public function index(int $bid = 1)
    {
        $building = Building::find($bid);
        $categories = Category::all();

        return view('main', [
            'building' => $building,
            'categories' => $categories,
        ]);
    }
}
