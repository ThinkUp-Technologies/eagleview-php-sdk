<?php

namespace ThinkUp\EagleView\Types\MeasurementRequests;

class PrimaryStructureOnly extends MeasurementRequest
{
    /**
     * The measurement instruction type id.
     *
     * @var string
     */
    public $value = 2;

    /**
     * The name of the measurement request.
     *
     * @var string
     */
    public $name = 'PrimaryStructureOnly';

    /**
     * The description of the measurement request.
     *
     * @var string
     */
    public $description = 'Request that only the primary structure be measured.';
}
