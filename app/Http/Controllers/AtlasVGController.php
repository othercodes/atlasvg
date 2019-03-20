<?php

namespace AtlasVG\Http\Controllers;

use AtlasVG\Models\Building;
use AtlasVG\Models\Category;
use AtlasVG\Models\Level;

class AtlasVGController extends Controller
{
    /**
     * Main index view
     * @param int $bid
     * @return \Illuminate\View\View
     */
    public function index(int $bid = 1)
    {
        /** @var Building $building */
        $building = Building::find($bid);
        $categories = Category::all();

        $environment = null;

        if (isset($building)) {
            $environment['level'] = $building->levels->reduce(function ($carry, Level $level) {
                $params = $level->calculateRelativeWidthAndHeight();

                if (!isset($carry['width']) || $params['width'] > $carry['width']) {
                    $carry['width'] = $params['width'];
                }

                if (!isset($carry['height']) || $params['height'] > $carry['height']) {
                    $carry['height'] = $params['height'];
                }

                if (!isset($carry['left']) || $params['left'] > ($carry['width'] / 2)) {
                    $carry['left'] = $params['width'] / 2;
                }

                if (!isset($carry['top']) || $params['top'] > ($carry['height'] / 2)) {
                    $carry['top'] = $params['height'] / 2;
                }

                return $carry;
            }, []);
        }

        return view('main', [
            'building' => $building,
            'categories' => $categories,
            'environment' => $environment
        ]);
    }
}
