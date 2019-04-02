<?php

namespace AtlasVG\Helpers;

use AtlasVG\Models\AuthData;
use Illuminate\Support\Facades\Log;

class Token {

    /**
     * saves token info for a successfully authenticated user
     * @param object $accessToken
     * @param object $user
     * @param int $bid
     */
    public function storeTokens($accessToken, $user, $bid) {

        $authdata = AuthData::updateOrCreate(
            ['building_id' => $bid],
            ['accessToken' => $accessToken->getToken(),
            'refreshToken' => $accessToken->getRefreshToken(),
            'tokenExpires' => $accessToken->getExpires(),
            'userName' => $user->getDisplayName(),
            'userEmail' => null !== $user->getMail() ? $user->getMail() : $user->getUserPrincipalName()]
        );
    }

    /**
     * returns a valid token
     * @return string $accessToken
     */
    public function getAccessToken($bid) {

        $authdata = AuthData::where('building_id', '=', $bid)->first();

        if (!$authdata) {
            throw new \Exception("No authentication data found, please go to /app/signin to log in.");
        }

        // token is valid for only 1h, we need to use refreshToken to regenerate it
        // just in case setting current time += 5 minutes (to allow for time differences)
        $now = time() + 300;

        if ($authdata->tokenExpires <= $now) {

            Log::debug("Token has expired, retrieving a new one...");

            # expired, let's refresh
            $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
                'clientId' => env('OAUTH_APP_ID'),
                'clientSecret' => env('OAUTH_APP_PASSWORD'),
                'redirectUri' => env('OAUTH_REDIRECT_URI'). $authdata->building_id,
                'urlAuthorize' => env('OAUTH_AUTHORITY') . env('OAUTH_AUTHORIZE_ENDPOINT'),
                'urlAccessToken' => env('OAUTH_AUTHORITY') . env('OAUTH_TOKEN_ENDPOINT'),
                'urlResourceOwnerDetails' => '',
                'scopes' => env('OAUTH_SCOPES'),
            ]);

            try {

                $newToken = $oauthClient->getAccessToken('refresh_token', [
                    'refresh_token' => $authdata->refreshToken,
                ]);

                Log::debug("Successfully aquired a new token, storing in db...");

                # storing new tokens for the same building
                $this->updateTokens($newToken, $authdata);

                return $newToken->getToken();
            } catch (League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
                # TODO: handle exceptions properly
                return '';
            }
        }

        // Token is still valid, just return it
        return $authdata->accessToken;
    }

    /**
     * updates token data in db
     * @param object $accessToken
     * @param object $authdata
     */
    private function updateTokens($accessToken, $authdata) {

        $authdata->accessToken = $accessToken->getToken();
        $authdata->refreshToken = $accessToken->getRefreshToken();
        $authdata->tokenExpires = $accessToken->getExpires();
        $authdata->save();

    }

}