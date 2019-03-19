<?php

namespace AtlasVG\Helpers;

use AtlasVG\Models\AuthData;

class Token {

    /**
     * saves token info for a successfully authenticated user
     * @param object $accessToken
     * @param object $user
     */
    public function storeTokens($accessToken, $user) {

        $authdata = new \AtlasVG\Models\AuthData([
            'accessToken' => $accessToken->getToken(),
            'refreshToken' => $accessToken->getRefreshToken(),
            'tokenExpires' => $accessToken->getExpires(),
            'userName' => $user->getDisplayName(),
            'userEmail' => null !== $user->getMail() ? $user->getMail() : $user->getUserPrincipalName(),
        ]);

        $authdata->save();
    }

    /**
     * returns a valid token
     * @return string $accessToken
     */
    public function getAccessToken() {

        $authdata = AuthData::select()->first();

        if (!$authdata) {
            throw new \Exception("No authentication data found, please go to /app/signin to log in.");
        }

        // token is valid for only 1h, we need to use refreshToken to regenerate it
        // just in case setting current time += 5 minutes (to allow for time differences)
        $now = time() + 300;

        if ($authdata->tokenExpires <= $now) {

            # expired, let's refresh
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
                $newToken = $oauthClient->getAccessToken('refresh_token', [
                    'refresh_token' => $authdata->refreshToken,
                ]);

                # updating token in db for future use
                $this->updateTokens($newToken);

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
     */
    public function updateTokens($accessToken) {

        # TODO: currently ignoring multiple entries in AuthData table, needs redesign
        $authdata = AuthData::select()->first();

        $authdata->accessToken = $accessToken->getToken();
        $authdata->refreshToken = $accessToken->getRefreshToken();
        $authdata->tokenExpires = $accessToken->getExpires();
        $authdata->save();

    }

}