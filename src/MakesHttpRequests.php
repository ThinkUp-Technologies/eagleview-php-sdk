<?php

namespace ThinkUp\EagleView;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use ThinkUp\EagleView\Exceptions\ApiServerException;
use ThinkUp\EagleView\Exceptions\FailedActionException;
use ThinkUp\EagleView\Exceptions\NotFoundException;
use ThinkUp\EagleView\Exceptions\TimeoutException;
use ThinkUp\EagleView\Exceptions\ValidationException;

trait MakesHttpRequests
{
    /**
     * Make a GET request to EagleView servers and return the response.
     *
     * @param string $uri
     * @param array $params
     * @return mixed
     * @throws ApiServerException
     * @throws FailedActionException
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function get(string $uri, array $params = [])
    {
        return $this->request('GET', $uri, $params);
    }

    /**
     * Make a POST request to EagleView servers and return the response.
     *
     * @param string $uri
     * @param array $payload
     * @return mixed
     * @throws ApiServerException
     * @throws FailedActionException
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function post(string $uri, array $payload = [])
    {
        return $this->request('POST', $uri, $payload);
    }

    /**
     * Make a PUT request to EagleView servers and return the response.
     *
     * @param string $uri
     * @param array $payload
     * @return mixed
     * @throws ApiServerException
     * @throws FailedActionException
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function put(string $uri, array $payload = [])
    {
        return $this->request('PUT', $uri, $payload);
    }

    /**
     * Make a DELETE request to EagleView servers and return the response.
     *
     * @param string $uri
     * @param array $payload
     * @return mixed
     * @throws ApiServerException
     * @throws FailedActionException
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function delete(string $uri, array $payload = [])
    {
        return $this->request('DELETE', $uri, $payload);
    }

    /**
     * Make a request to EagleView servers and return the response.
     *
     * @param string $verb
     * @param string $uri
     * @param array $payload
     * @return mixed
     * @throws ApiServerException
     * @throws FailedActionException
     * @throws GuzzleException
     * @throws NotFoundException
     * @throws ValidationException
     */
    protected function request(string $verb, string $uri, array $payload = [])
    {
        if (!empty($payload)) {
            if ($verb === 'GET') {
                $payload = ['query' => $payload];
            }
        }

        $response = $this->guzzle->request($verb, $uri, $payload);

        $statusCode = $response->getStatusCode();

        if ($statusCode < 200 || $statusCode > 299) {
            return $this->handleRequestError($response);
        }

        $responseBody = (string)$response->getBody();

        return json_decode($responseBody, true) ?: $responseBody;
    }

    /**
     * Handle a request error.
     *
     * @param ResponseInterface $response
     * @return void
     *
     * @throws Exception
     * @throws FailedActionException
     * @throws NotFoundException
     * @throws ValidationException
     * @throws ApiServerException
     */
    protected function handleRequestError(ResponseInterface $response)
    {
        if ($response->getStatusCode() == 422) {
            throw new ValidationException(json_decode((string)$response->getBody(), true));
        }

        if ($response->getStatusCode() == 404) {
            throw new NotFoundException();
        }

        if ($response->getStatusCode() == 400) {
            throw new FailedActionException((string)$response->getBody());
        }

        if ($response->getStatusCode() >= 500) {
            throw ApiServerException::forResponse($response);
        }

        throw new Exception((string)$response->getBody());
    }

    /**
     * Retry the callback or fail after x seconds.
     *
     * @param int $timeout
     * @param callable $callback
     * @param int $sleep
     * @return mixed
     *
     * @throws TimeoutException
     */
    public function retry($timeout, $callback, $sleep = 5)
    {
        $start = time();

        beginning:

        if ($output = $callback()) {
            return $output;
        }

        if (time() - $start < $timeout) {
            sleep($sleep);

            goto beginning;
        }

        throw new TimeoutException($output);
    }
}
