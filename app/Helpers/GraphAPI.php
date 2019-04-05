<?php

namespace AtlasVG\Helpers;

use AtlasVG\Models\AuthData;
use Illuminate\Support\Facades\Log;
use League\OAuth2\Client\Provider\GenericProvider;
use Microsoft\Graph\Graph;

class GraphAPI {

    protected $authdata;

    public function __construct($bid) {

        $this->authdata = AuthData::firstOrCreate(
            ['building_id' => $bid]
        );
    }

    /**
     * generates auth URL for this app + permissions scope to redirect the user to log in
     * @return string $url
     */
    public function getRedirectUrl() {

        $oauthClient = $this->createOauthClient();

        return $oauthClient->getAuthorizationUrl();
    }

    /**
     * retrieves tokens from GraphAPI based on authorization code and saves them in db
     * @param string $authCode
     */
    public function generateTokens($authCode) {

        $oauthClient = $this->createOauthClient();

        $accessToken = $oauthClient->getAccessToken('authorization_code', [
            'code' => $authCode,
        ]);

        $this->authdata->accessToken = $accessToken->getToken();

        $current_user = $this->sendRequest('/me');

        $this->saveTokens($accessToken, $current_user);
    }

    /**
     * sends a GET request to Graph API 
     * @param string $url
     * @return \Illuminate\Http\Response
     */
    public function sendRequest($url, $isRawResponse = false) {

        $graph = new Graph();
        $graph->setAccessToken($this->getAccessToken());

        $response = $graph->createRequest('GET', $url)
            ->addHeaders(array("Content-Type" => "application/json"))
            ->execute();

        return $isRawResponse ? $response->getRawBody() : $response->getBody();
    }

    /**
     * initializes oauth client for Graph API
     * @return \League\OAuth2\Client\Provider\GenericProvider
     */
    private function createOauthClient() {

        return new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId' => env('OAUTH_APP_ID'),
            'clientSecret' => env('OAUTH_APP_PASSWORD'),
            'redirectUri' => env('OAUTH_REDIRECT_URI') . $this->authdata->building_id,
            'urlAuthorize' => 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize',
            'urlAccessToken' => 'https://login.microsoftonline.com/common/oauth2/v2.0/token',
            'urlResourceOwnerDetails' => '',
            'scopes' => env('OAUTH_SCOPES'),
        ]);
    }

    /**
     * saves token info for a successfully authenticated user
     * @param object $accessToken
     * @param object $user
     */
    private function saveTokens($accessToken, $user) {

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

        if ($this->authdata->tokenExpires && $this->authdata->tokenExpires <= $now) {

            Log::debug("Token has expired, retrieving a new one...");

            $oauthClient = $this->createOauthClient();

            $newToken = $oauthClient->getAccessToken('refresh_token', [
                'refresh_token' => $this->authdata->refreshToken,
            ]);

            Log::debug("Successfully aquired a new token, storing in db...");

            $this->authdata = AuthData::updateOrCreate(
                ['building_id' => $this->authdata->building_id],
                ['accessToken' => $newToken->getToken(),
                'refreshToken' => $newToken->getRefreshToken(),
                'tokenExpires' => $newToken->getExpires()]
            );

        }

        return $this->authdata->accessToken;
    }

}