<?php

namespace ThinkUp\EagleView\Types\MeasurementRequests;

class Other extends MeasurementRequest
{
    /**
     * The measurement instruction type id.
     *
     * @var string
     */
    public $value = 5;

    /**
     * The name of the measurement request.
     *
     * @var string
     */
    public $name = 'CommercialComplex';

    /**
     * The description of the measurement request.
     *
     * @var string
     */
    public $description = 'Request that does not fit any of the other measurement request types.';
}
