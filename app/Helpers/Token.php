<?php

namespace AtlasVG\Helpers;

use AtlasVG\Models\AuthData;
use Illuminate\Support\Facades\Log;
use League\OAuth2\Client\Provider\GenericProvider;
use Microsoft\Graph\Graph;
//use Microsoft\Graph\Model;

class Token {

    protected $authdata;

    public function __construct($bid) {

        $this->authdata = AuthData::firstOrCreate(
            ['building_id' => $bid]
        );

        Log::info("Authdata: " . json_encode($this->authdata, JSON_PRETTY_PRINT));
    }

    public function getRedirectUrl() {

        $oauthClient = $this->createOauthClient();

        return $oauthClient->getAuthorizationUrl();
    }

    public function generateTokens($authCode) {

        $oauthClient = $this->createOauthClient();

        $accessToken = $oauthClient->getAccessToken('authorization_code', [
            'code' => $authCode,
        ]);

        $this->authdata->accessToken = $accessToken->getToken();

        $current_user = $this->sendRequest('/me');

        $this->saveTokens($accessToken, $current_user);
    }

    public function sendRequest($url) {

        $graph = new Graph();
        $graph->setAccessToken($this->getAccessToken());

        $response = $graph->createRequest('GET', $url)
            ->addHeaders(array("Content-Type" => "application/json"))
            ->execute();

        return $response->getBody();
    }

    private function createOauthClient() {

        return new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId' => env('OAUTH_APP_ID'),
            'clientSecret' => env('OAUTH_APP_PASSWORD'),
            'redirectUri' => env('OAUTH_REDIRECT_URI') . $this->authdata->building_id,
            'urlAuthorize' => env('OAUTH_AUTHORITY') . env('OAUTH_AUTHORIZE_ENDPOINT'),
            'urlAccessToken' => env('OAUTH_AUTHORITY') . env('OAUTH_TOKEN_ENDPOINT'),
            'urlResourceOwnerDetails' => '',
            'scopes' => env('OAUTH_SCOPES'),
        ]);
    }

    /**
     * saves token info for a successfully authenticated user
     * @param object $accessToken
     * @param object $user
     * @param int $bid
     */
    private function saveTokens($accessToken, $user) {

    Log::info("User: " . json_encode($user, JSON_PRETTY_PRINT));

        $this->authdata = AuthData::updateOrCreate(
            ['building_id' => $this->authdata->building_id],
            ['accessToken' => $accessToken->getToken(),
            'refreshToken' => $accessToken->getRefreshToken(),
            'tokenExpires' => $accessToken->getExpires(),
            'userName' => $user['displayName'],
            'userEmail' => $user['userPrincipalName']]
        );
    }

    /**
     * returns a valid token
     * @return string $accessToken
     */
    private function getAccessToken() {

        //$authdata = AuthData::where('building_id', '=', $this->bid)->first();

        if (!$this->authdata->accessToken) {
            throw new \Exception("No authentication data found, please go to /app/signin to log in.");
        }

        // token is valid for only 1h, we need to use refreshToken to regenerate it
        // just in case setting current time += 5 minutes (to allow for time differences)
        $now = time() + 300;

        if ($this->authdata->tokenExpires <= $now) {

            Log::debug("Token has expired, retrieving a new one...");

            # expired, let's refresh
            $oauthClient = $this->createOauthClient();

            $newToken = $oauthClient->getAccessToken('refresh_token', [
                'refresh_token' => $this->authdata->refreshToken,
            ]);

            Log::debug("Successfully aquired a new token, storing in db...");

            //$this->authdata->accessToken = $accessToken->getToken();
            //$this->authdata->refreshToken = $accessToken->getRefreshToken();
            //$this->authdata->tokenExpires = $accessToken->getExpires();
            //$this->authdata->save();

            $this->authdata = AuthData::updateOrCreate(
                ['building_id' => $this->authdata->building_id],
                ['accessToken' => $newToken->getToken(),
                'refreshToken' => $newToken->getRefreshToken(),
                'tokenExpires' => $newToken->getExpires()]
            );

            //return $newToken->getToken();

        }

        return $this->authdata->accessToken;
    }

}