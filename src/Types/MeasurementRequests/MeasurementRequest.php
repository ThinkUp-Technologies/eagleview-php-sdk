<?php

namespace ThinkUp\EagleView\Types\MeasurementRequests;

abstract class MeasurementRequest
{
    /**
     * The measurement instruction type id.
     *
     * @var string
     */
    public $value;

    /**
     * The name of the measurement request.
     *
     * @var string
     */
    public $name;

    /**
     * The description of the measurement request.
     *
     * @var string
     */
    public $description;

    /**
     * The array representation of the type.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
