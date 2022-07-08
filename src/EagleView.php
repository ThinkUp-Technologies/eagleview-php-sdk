<?php

namespace ThinkUp\EagleView;

use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client as HttpClient;
use ThinkUp\EagleView\Actions;
use ThinkUp\EagleView\Resources\Token;

class EagleView
{
    use MakesHttpRequests;
    use Actions\ManagesTokens;
    use Actions\ManagesProducts;

    /**
     * The base endpoint for all API calls to EagleView.
     *
     * @var string
     */
    protected $endpoint = 'https://webservices-integrations.eagleview.com';

    /**
     * The HTTP Client instance.
     *
     * @var HttpClient
     */
    public $guzzle;

    /**
     * Number of seconds a request is retried.
     *
     * @var int
     */
    public $timeout = 30;

    /**
     * The token instance to use to make authenticated API calls and perform token refreshes.
     *
     * @var Token|null
     */
    public $token = null;

    /**
     * Create a new EagleView SDK instance.
     *
     * @param string|null $endpoint Base endpoint to use for all API calls. Defaults to test environment.
     */
    public function __construct(?string $endpoint = null)
    {
        if (!is_null($endpoint)) {
            $this->endpoint = $endpoint;
        }

        $this->guzzle = new HttpClient([
            'base_uri' => $this->endpoint,
            'http_errors' => false,
        ]);
    }

    /**
     * Log in to EagleView's REST API.
     *
     * In short, this will create a new access token and set it up to be used on subsequent calls.
     *
     * @param string $username Email address used to log in to EagleView website.
     * @param string $password Password used to log in to EagleView website.
     * @param string $sourceId Unique identifier provided by EagleView Integration team.
     * @param string $clientSecret Unique secret provided by EagleView Integration team.
     * @param string|null $endpoint Base endpoint to use for all API calls. Defaults to test environment.
     * @return static
     * @throws Exception
     */
    public static function login(string $username, string $password, string $sourceId, string $clientSecret, ?string $endpoint = null): self
    {
        $eagleView = new self($endpoint);

        $token = $eagleView->createToken($username, $password, $sourceId, $clientSecret);

        return static::withToken($token, $endpoint);
    }

    /**
     * Create a new EagleView SDK instance with the given token instance.
     *
     * @param Token $token
     * @param string|null $endpoint Base endpoint to use for all API calls. Defaults to test environment.
     * @return static
     */
    public static function withToken(Token $token, ?string $endpoint = null): self
    {
        $eagleView = new self($endpoint);

        $eagleView->token = $token;

        $eagleView->guzzle = new HttpClient([
            'base_uri' => $eagleView->endpoint,
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $eagleView->token->access_token,
            ],
        ]);

        return $eagleView;
    }

    /**
     * Create a new EagleView SDK instance with the given access token (bearer token).
     *
     * @param string $token
     * @param string|null $endpoint Base endpoint to use for all API calls. Defaults to test environment.
     * @return static
     */
    public static function withRawToken(string $token, ?string $endpoint = null): self
    {
        $eagleView = new self($endpoint);

        $eagleView->guzzle = new HttpClient([
            'base_uri' => $eagleView->endpoint,
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return $eagleView;
    }

    /**
     * Determines if the current token being used is expired or is about to expire.
     *
     * @return bool
     */
    public function isTokenExpired(): bool
    {
        if (is_null($this->token)) {
            return true;
        }

//        Carbon::parse()
    }

    /**
     * Transform the items of the collection to the given class.
     *
     * @param array $collection
     * @param string $class
     * @param array $extraData
     * @return array
     */
    protected function transformCollection($collection, $class, $extraData = [])
    {
        return array_map(function ($data) use ($class, $extraData) {
            return new $class($data + $extraData, $this);
        }, $collection);
    }

    /**
     * Set a new timeout.
     *
     * @param int $timeout
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Get the timeout.
     *
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }
}