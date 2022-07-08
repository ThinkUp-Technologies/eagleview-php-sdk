<?php

namespace ThinkUp\EagleView\Resources;

class Token extends Resource
{
    /**
     * The access token (bearer token) to use for authenticated API calls to EagleView.
     *
     * @var string
     */
    public $access_token;

    /**
     * The type of token provided. This will pretty much always be "bearer".
     *
     * @var string
     */
    public $token_type;

    /**
     * The number of seconds the access token expires.
     *
     * @var int
     */
    public $expires_in;

    /**
     * The refresh token to use for re-fetching a fresh access token (bearer token).
     *
     * @var string
     */
    public $refresh_token;
}
