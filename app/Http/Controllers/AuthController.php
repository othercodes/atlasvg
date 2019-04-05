<?php

namespace AtlasVG\Http\Controllers;

use AtlasVG\Http\Controllers\Controller;
use AtlasVG\Helpers\GraphAPI;
use AtlasVG\Models\Building;
use AtlasVG\Helpers\GraphAPI;
use Illuminate\Http\Request;

class AuthController extends Controller {

    public function signin($bid = null) {

        $bid = RemoteData::validate_bid($bid);
        $api = new GraphAPI($bid);

        $authUrl = $api->getRedirectUrl();

        # redirect to AAD passing this app's client id and secret,
        # o365 will call callback() in case of successful auth
        # providing code that can be used to generate a token
        return redirect($authUrl);
    }

    public function callback(Request $request, $bid = null) {

        $bid = RemoteData::validate_bid($bid);
        // authorization code should be in the "code" query param
        $authCode = $request->query('code');

        $api = new GraphAPI($bid);
        $api->generateTokens($authCode);

        return redirect('/app/sync/' .$bid);

    }
}