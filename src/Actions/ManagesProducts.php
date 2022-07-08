<?php

namespace ThinkUp\EagleView\Actions;

use GuzzleHttp\Exception\GuzzleException;
use ThinkUp\EagleView\Exceptions\ApiServerException;
use ThinkUp\EagleView\Exceptions\FailedActionException;
use ThinkUp\EagleView\Exceptions\NotFoundException;
use ThinkUp\EagleView\Exceptions\ValidationException;
use ThinkUp\EagleView\Resources\Product;

trait ManagesProducts
{
    /**
     * The GetAvailableProducts method is useful in determining which products
     * are available to the authenticating customer.
     *
     * This method is optional if it is already known which products are
     * enabled on the authenticating user's account.
     *
     * @param array|null $parameters
     * @return array<Product>
     * @throws GuzzleException
     * @throws ApiServerException
     * @throws FailedActionException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function getAvailableProducts(?array $parameters = [])
    {
        return $this->transformCollection(
            $this->get('v2/Product/GetAvailableProducts', $parameters), Product::class
        );
    }
}
