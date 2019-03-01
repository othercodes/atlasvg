<?php

namespace App\Http\Controllers;

class AtlasVGController extends Controller
{

    public function index()
    {
        $maps = [];
        foreach (['sample1.svg', 'sample2.svg'] as $i => $m) {
            $maps[$i + 1] = $this->getMap($m);
        }

        return view('index', [
            'maps' => $maps
        ]);
    }
}
