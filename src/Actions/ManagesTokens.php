<?php

namespace ThinkUp\EagleView\Actions;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use ThinkUp\EagleView\Exceptions\ApiServerException;
use ThinkUp\EagleView\Exceptions\FailedActionException;
use ThinkUp\EagleView\Exceptions\NotFoundException;
use ThinkUp\EagleView\Exceptions\ValidationException;
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
     * @throws GuzzleException
     * @throws ApiServerException
     * @throws FailedActionException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function createToken(string $username, string $password, string $sourceId, string $clientSecret): Token
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
     * @throws ApiServerException
     * @throws FailedActionException
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function refreshToken(string $refreshToken, string $sourceId, string $clientSecret): Token
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
