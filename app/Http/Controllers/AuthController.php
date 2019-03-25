<?php

namespace AtlasVG\Http\Controllers;

use AtlasVG\Http\Controllers\Controller;
use AtlasVG\Models\Building;
use AtlasVG\Helpers\Token;
use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller {

    public function signin($bid = null) {

        $bid = $this->validate_bid($bid);

        $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId' => env('OAUTH_APP_ID'),
            'clientSecret' => env('OAUTH_APP_PASSWORD'),
            'redirectUri' => env('OAUTH_REDIRECT_URI') . $bid,
            'urlAuthorize' => env('OAUTH_AUTHORITY') . env('OAUTH_AUTHORIZE_ENDPOINT'),
            'urlAccessToken' => env('OAUTH_AUTHORITY') . env('OAUTH_TOKEN_ENDPOINT'),
            'urlResourceOwnerDetails' => '',
            'scopes' => env('OAUTH_SCOPES'),
        ]);

        $authUrl = $oauthClient->getAuthorizationUrl();

        # redirect to AAD passing this app's client id and secret,
        # o365 will call callback() in case of successful auth
        # providing code that can be used to generate a token
        return redirect($authUrl);
    }

    public function callback(Request $request, $bid = null) {

        $bid = $this->validate_bid($bid);

        // authorization code should be in the "code" query param
        $authCode = $request->query('code');

        if (isset($authCode)) {

            $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
                'clientId' => env('OAUTH_APP_ID'),
                'clientSecret' => env('OAUTH_APP_PASSWORD'),
                'redirectUri' => env('OAUTH_REDIRECT_URI') . $bid,
                'urlAuthorize' => env('OAUTH_AUTHORITY') . env('OAUTH_AUTHORIZE_ENDPOINT'),
                'urlAccessToken' => env('OAUTH_AUTHORITY') . env('OAUTH_TOKEN_ENDPOINT'),
                'urlResourceOwnerDetails' => '',
                'scopes' => env('OAUTH_SCOPES'),
            ]);

            try {

                // Make the token request
                $accessToken = $oauthClient->getAccessToken('authorization_code', [
                    'code' => $authCode,
                ]);

                $graph = new Graph();
                $graph->setAccessToken($accessToken->getToken());

                # getting token's holder info for audit purposes to know who synced user data
                $user = $graph->createRequest('GET', '/me')
                    ->setReturnType(Model\User::class)
                    ->execute();

                $token = new Token();
                $token->storeTokens($accessToken, $user, $bid);

                # once auth is done and token successfully saved in db, we can repeatedly sync through /sync
                return redirect('/app/sync/' .$bid);

            } catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
                # TODO: error handling if something is wrong with the token
                # for now silently redirecting to /
                return redirect('/');
            }
        }

        # silently redirect to homepage if someone goes to callback page by mistake, by design :)
        return redirect('/');
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