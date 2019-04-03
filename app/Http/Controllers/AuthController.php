<?php

namespace AtlasVG\Http\Controllers;

use AtlasVG\Http\Controllers\Controller;
use AtlasVG\Models\Building;
use AtlasVG\Helpers\Token;
use Illuminate\Http\Request;

class AuthController extends Controller {

    public function signin($bid = null) {

        $bid = $this->validate_bid($bid);
        $token = new Token($bid);

        $authUrl = $token->getRedirectUrl();

        # redirect to AAD passing this app's client id and secret,
        # o365 will call callback() in case of successful auth
        # providing code that can be used to generate a token
        return redirect($authUrl);
    }

    public function callback(Request $request, $bid = null) {

        $bid = $this->validate_bid($bid);
        // authorization code should be in the "code" query param
        $authCode = $request->query('code');

        $token = new Token($bid);
        $token->generateTokens($authCode);

        return redirect('/app/sync/' .$bid);

    }

    private function validate_bid($bid = null) {

        # if no building id is passed, defaulting to the first one
        if (!$bid) {
            $bid = Building::select()->first()->id;
        # if building id is specified but doesn't exist throwing 404
        } elseif (!Building::find($bid)) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
        }

        return $bid;
    }
}