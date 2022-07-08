<?php

namespace ThinkUp\EagleView\Types\MeasurementRequests;

class CommercialComplex extends MeasurementRequest
{
    /**
     * The measurement instruction type id.
     *
     * @var string
     */
    public $value = 4;

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
    public $description = 'Request that notes the structure to be measured is a commercial complex.';
}
