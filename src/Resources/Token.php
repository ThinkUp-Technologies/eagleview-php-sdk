<?php

namespace ThinkUp\EagleView\Resources;

use Carbon\Carbon;

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

    /**
     * The UTC datetime of when the token was issued.
     *
     * @var string
     */
    public $issued_at;

    /**
     * The UTC datetime of when the token will expire.
     *
     * @var string
     */
    public $expires_at;

    /**
     * Create a new token instance from the given values.
     *
     * @param string $access_token
     * @param string $refresh_token
     * @param string $issued_at
     * @param string $expires_at
     * @return static
     */
    public static function create(string $access_token, string $refresh_token, string $issued_at, string $expires_at): self
    {
        return new self(compact('access_token', 'refresh_token', 'issued_at', 'expires_at'));
    }

    /**
     * Determine if the token needs to be refreshed.
     *
     * @return bool
     */
    public function needsRefresh(): bool
    {
        return Carbon::now()->subSeconds(30)->gt(Carbon::parse($this->expires_at));
    }

    /**
     * Defines how to fill the "issued_at" property on our Token class.
     *
     * @param string $datetime
     * @return void
     */
    protected function fillIssuedAttribute(string $datetime): void
    {
        $this->issued_at = Carbon::parse($datetime)->toDateTimeString();

        $this->attributes['issued_at'] = $this->issued_at;
    }

    /**
     * Defines how to fill the "expires_at" property on our Token class.
     *
     * @param string $datetime
     * @return void
     */
    protected function fillExpiresAttribute(string $datetime): void
    {
        $this->expires_at = Carbon::parse($datetime)->toDateTimeString();

        $this->attributes['expires_at'] = $this->expires_at;
    }
}
