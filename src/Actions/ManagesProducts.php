<?php

namespace ThinkUp\EagleView\Actions;

use Exception;
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
     * @return array<Product>
     * @throws Exception
     */
    public function getAvailableProducts(?array $parameters = [])
    {
        return $this->transformCollection(
            $this->get('v2/Product/GetAvailableProducts', $parameters), Product::class
        );
    }
}
