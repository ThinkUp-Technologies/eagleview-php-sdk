<?php

namespace ThinkUp\EagleView\Actions;

use GuzzleHttp\Exception\GuzzleException;
use ThinkUp\EagleView\Exceptions\ApiServerException;
use ThinkUp\EagleView\Exceptions\FailedActionException;
use ThinkUp\EagleView\Exceptions\NotFoundException;
use ThinkUp\EagleView\Exceptions\ValidationException;
use ThinkUp\EagleView\Resources\Product;

trait ManagesOrders
{
    /**
     * The PlaceOrder method is the primary mechanism of the ordering process, as
     * it will actually create the order. A successful PlaceOrder call will return
     * a unique Report ID, which can then be used in other method calls.
     *
     * @see https://restdoc.eagleview.com/#PlaceOrder
     *
     * @param array $payload All data necessary to place the order.
     * @return array
     * @throws GuzzleException
     * @throws ApiServerException
     * @throws FailedActionException
     * @throws NotFoundException
     * @throws ValidationException
     */
    public function placeOrder(array $payload): array
    {
        return $this->post('v2/Order/PlaceOrder', [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($payload),
        ]);
    }
}
