<?php

namespace AtlasVG\Http\Controllers;

class BuildingController extends Controller
{
    public function index()
    {
        return view('admin.buildings', [
            'msg' => 'hello',
        ]);
    }
}
