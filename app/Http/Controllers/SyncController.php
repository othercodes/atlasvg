<?php

namespace AtlasVG\Http\Controllers;

use AtlasVG\Http\Controllers\Controller;

class SyncController extends Controller {

    public function sync(int $bid = 1) {

        $result = \AtlasVG\Helpers\RemoteData::sync($bid);
        
        # TODO: display $result with a simple template
        return response()->json($result);
    }
}