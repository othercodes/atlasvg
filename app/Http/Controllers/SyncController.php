<?php

namespace AtlasVG\Http\Controllers;

use AtlasVG\Http\Controllers\Controller;

class SyncController extends Controller {

    public function sync() {

        $result = \AtlasVG\Helpers\RemoteData::sync();
        
        # TODO: display $result with a simple template
        return response()->json($result);
    }
}