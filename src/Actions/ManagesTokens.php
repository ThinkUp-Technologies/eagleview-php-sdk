<?php

namespace ThinkUp\EagleView\Actions;

use Exception;
use ThinkUp\EagleView\Resources\Token;

trait ManagesTokens
{
    /**
     * Create a new access token (bearer token).
     *
     * @param string $username Email address used to log in to EagleView website.
     * @param string $password Password used to log in to EagleView website.
     * @param string $sourceId Unique identifier provided by EagleView Integration team.
     * @param string $clientSecret Unique secret provided by EagleView Integration team.
     * @return Token
     * @throws Exception
     */
    public function createToken(string $username, string $password, string $sourceId, string $clientSecret)
    {
        $basicAuth = base64_encode($sourceId . ':' . $clientSecret);

        $response = $this->post('Token', [
            'headers' => [
                'Authorization' => 'Basic ' . $basicAuth,
            ],
            'form_params' => [
                'grant_type' => 'password',
                'username' => $username,
                'password' => $password,
            ]
        ]);

        return new Token($response, $this);
    }

    /**
     * Re-fetch a new access token (bearer token) using a refresh token.
     *
     * @param string $refreshToken The refresh token to use for re-fetching a new access token.
     * @param string $sourceId Unique identifier provided by EagleView Integration team.
     * @param string $clientSecret Unique secret provided by EagleView Integration team.
     * @return Token
     * @throws Exception
     */
    public function refreshToken(string $refreshToken, string $sourceId, string $clientSecret)
    {
        $basicAuth = base64_encode($sourceId . ':' . $clientSecret);

        $response = $this->post('Token', [
            'headers' => [
                'Authorization' => 'Basic ' . $basicAuth,
            ],
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
            ]
        ]);

        return new Token($response, $this);
    }
}
