<?php

declare(strict_types = 1);

namespace Wefabric\Acronis;

use Carbon\Carbon;
use GuzzleHttp\Client;

final class BearerToken
{

    /**
     * Authentication URI
     */
    const AUTH_URI = '/api/{apiVersion}/idp/token';

    /**
     * @param Credentials $credentials
     * @param UrlResolver $urlResolver
     * @return Credentials
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getBearerToken(Credentials $credentials, UrlResolver $urlResolver): Credentials
    {
        $clientId = $credentials->getClientId();
        $clientSecret = $credentials->getClientSecret();
        $authCredentials = base64_encode("{$clientId}:$clientSecret");

        $headers = [
            'Authorization' => "Basic $authCredentials"
        ];

        $response = (new Client())->post($urlResolver->resolve(self::AUTH_URI), [
            'form_params' => ['grant_type' => 'client_credentials'],
            'headers' => $headers
        ]);

        $authorization = json_decode((string)$response->getBody(), true);

        if(isset($authorization['access_token'])) {
            $credentials->setAccessToken($authorization['access_token']);
        }
        if(isset($authorization['expires_in'])) {
            $credentials->setExpiresIn((int)$authorization['expires_in']);
        }
        if(isset($authorization['expires_on'])) {
            $credentials->setExpiresAt(new Carbon($authorization['expires_on']));
        }

        return $credentials;
    }

}