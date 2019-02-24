<?php

namespace App\Http\Controllers;

use SVG\SVG;

class AtlasVGController extends Controller
{

    public function index()
    {
        $map = SVG::fromString($this->getMap('sample.svg'));

        // need some magic to process the svg

        return view('index', ['map' => $map]);
    }
}
