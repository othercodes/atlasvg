<?php

namespace App\Http\Controllers;

use SVG\Nodes\Shapes\SVGCircle;
use SVG\SVG;

class AtlasVGController extends Controller
{

    public function index()
    {
        $map = SVG::fromString($this->getMap('sample.svg'));

        // need some magic to process the svg

        // get the dom document from the svg
        $doc = $map->getDocument();

        // test circle with radius 20 and green border, center at (50, 50)
        $doc->addChild((new SVGCircle(50, 50, 20))
            ->setStyle('fill', 'none')
            ->setStyle('stroke', '#0F0')
            ->setStyle('stroke-width', '2px'));

        return view('index', ['map' => $map]);
    }
}
