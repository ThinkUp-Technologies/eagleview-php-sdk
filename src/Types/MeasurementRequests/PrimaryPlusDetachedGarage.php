<?php

namespace ThinkUp\EagleView\Types\MeasurementRequests;

class PrimaryPlusDetachedGarage extends MeasurementRequest
{
    /**
     * The measurement instruction type id.
     *
     * @var string
     */
    public $value = 1;

    /**
     * The name of the measurement request.
     *
     * @var string
     */
    public $name = 'PrimaryPlusDetachedGarage';

    /**
     * The description of the measurement request.
     *
     * @var string
     */
    public $description = 'Request that the primary structure and the detached garage be measured.';
}
