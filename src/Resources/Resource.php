<?php

namespace ThinkUp\EagleView\Resources;

use ThinkUp\EagleView\EagleView;

class Resource
{
    /**
     * The resource attributes.
     *
     * @var array
     */
    public $attributes;

    /**
     * The EagleView SDK instance.
     *
     * @var EagleView|null
     */
    protected $eagleView;

    /**
     * Create a new resource instance.
     *
     * @param array $attributes
     * @param EagleView|null $eagleView
     */
    public function __construct(array $attributes, EagleView $eagleView = null)
    {
        $this->attributes = $attributes;
        $this->eagleView = $eagleView;

        $this->fill();
    }

    /**
     * Get the string representation of the resource.
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->attributes);
    }

    /**
     * Get the array representation of the resource.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * Dump the resource.
     *
     * @return void
     */
    public function dump()
    {
        print_r($this->toArray());
    }

    /**
     * Dump the resource and terminate the script.
     *
     * @return void
     */
    public function dd()
    {
        $this->dump();

        die();
    }

    /**
     * Fill the resource with the array of attributes.
     *
     * @return void
     */
    protected function fill()
    {
        foreach ($this->attributes as $key => $value) {
            $customFill = $this->standardizedFillMethodName($key);

            if (method_exists($this, $customFill)) {
                $this->{$customFill}($value);
            } else {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Defines how custom fill method names are named based on a given property.
     *
     * @param string $property
     * @return string
     */
    protected function standardizedFillMethodName(string $property): string
    {
        $property = ucwords($property);
        $property = str_replace('-', ' ', $property);
        $property = str_replace('_', ' ', $property);
        $property = preg_replace('/[^A-Za-z0-9\-]/', ' ', $property);
        $property = ucwords($property);
        $property = str_replace(' ', '', $property);

        return 'fill' . $property . 'Attribute';
    }

    /**
     * Transform the items of the collection to the given class.
     *
     * @param array $collection
     * @param string $class
     * @param array $extraData
     * @return array
     */
    protected function transformCollection(array $collection, $class, array $extraData = [])
    {
        return array_map(function ($data) use ($class, $extraData) {
            return new $class($data + $extraData, $this->eagleView);
        }, $collection);
    }

    /**
     * Transform the collection of tags to a string.
     *
     * @param array $tags
     * @param string|null $separator
     * @return string
     */
    protected function transformTags(array $tags, $separator = null)
    {
        $separator = $separator ?: ', ';

        return implode($separator, array_column($tags ?? [], 'name'));
    }
}
