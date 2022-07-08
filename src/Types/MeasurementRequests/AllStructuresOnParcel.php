<?php

namespace ThinkUp\EagleView\Types\MeasurementRequests;

class AllStructuresOnParcel extends MeasurementRequest
{
    /**
     * The measurement instruction type id.
     *
     * @var string
     */
    public $value = 3;

    /**
     * The name of the measurement request.
     *
     * @var string
     */
    public $name = 'AllStructuresOnParcel';

    /**
     * The description of the measurement request.
     *
     * @var string
     */
    public $description = 'Request that all structures on the parcel be measured.';
}
