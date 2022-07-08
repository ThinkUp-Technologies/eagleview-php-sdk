<?php

namespace ThinkUp\EagleView\Exceptions;

use Exception;
use Psr\Http\Message\ResponseInterface;

class ApiServerException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param ResponseInterface $response
     * @return static
     */
    public static function forResponse(ResponseInterface $response): self
    {
        $statusCode = $response->getStatusCode();
        $errorMessage = (string)$response->getBody();

        $exception = new self();

        $exception->message = "Looks like there was a problem on EagleView's end. You did nothing wrong. The server returned a "
            . $statusCode . ' with the message "' . $errorMessage . '"';

        return $exception;
    }
}
