<?php

namespace AtlasVG\Http\Controllers;

use AtlasVG\Http\Controllers\Controller;
use AtlasVG\Helpers\Token;
use Illuminate\Http\Request;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

class AuthController extends Controller {

    public function signin() {

        $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId' => env('OAUTH_APP_ID'),
            'clientSecret' => env('OAUTH_APP_PASSWORD'),
            'redirectUri' => env('OAUTH_REDIRECT_URI'),
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

    public function callback(Request $request) {

        // authorization code should be in the "code" query param
        $authCode = $request->query('code');

        if (isset($authCode)) {

            $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
                'clientId' => env('OAUTH_APP_ID'),
                'clientSecret' => env('OAUTH_APP_PASSWORD'),
                'redirectUri' => env('OAUTH_REDIRECT_URI'),
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
                $token->storeTokens($accessToken, $user);

                # once auth is done and token successfully saved in db, we can repeatedly sync through /sync
                return redirect('/app/sync');

            } catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
                # TODO: error handling if something is wrong with the token
                # for now silently redirecting to /
                return redirect('/');
            }
        }

        # silently redirect to homepage if someone goes to callback page by mistake, by design :)
        return redirect('/');
    }

}