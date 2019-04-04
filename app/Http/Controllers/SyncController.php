<?php

namespace AtlasVG\Http\Controllers;

use AtlasVG\Http\Controllers\Controller;

class SyncController extends Controller {

    public function sync($bid = null) {

        $result = \AtlasVG\Helpers\RemoteData::sync_data($bid);
        
        # TODO: display $result with a simple template
        return response()->json($result);
    }
}