<?php

namespace ThinkUp\EagleView\Resources;

use ThinkUp\EagleView\Types\MeasurementRequests\AllStructuresOnParcel;
use ThinkUp\EagleView\Types\MeasurementRequests\CommercialComplex;
use ThinkUp\EagleView\Types\MeasurementRequests\MeasurementRequest;
use ThinkUp\EagleView\Types\MeasurementRequests\Other;
use ThinkUp\EagleView\Types\MeasurementRequests\PrimaryPlusDetachedGarage;
use ThinkUp\EagleView\Types\MeasurementRequests\PrimaryStructureOnly;

class Product extends Resource
{
    /**
     * The id of the product.
     *
     * @var int
     */
    public $productID;

    /**
     * The name of the product.
     *
     * @var string
     */
    public $name;

    /**
     * The product description.
     *
     * @var string
     */
    public $description;

    /**
     * The product description in detail.
     *
     * @var string
     */
    public $DetailedDescription;

    /**
     * Product group name. This field is depreciated and in most cases will return a null value.
     *
     * @var string|null
     */
    public $productGroup;

    /**
     * Indicates if the product is temporarily unavailable.
     *
     * @var boolean
     */
    public $isTemporarilyUnavailable;

    /**
     * Value of the minimum price.
     *
     * @var float
     */
    public $priceMin;

    /**
     * Value of the maximum price.
     *
     * @var float
     */
    public $priceMax;

    /**
     * The id of the structure type.
     *
     * @var int
     */
    public $TypeOfStructure;

    /**
     * Indicates if this is a roof product.
     *
     * @var bool
     */
    public $IsRoofProduct;

    /**
     * Order to display products.
     *
     * @var int
     */
    public $SortOrder;

    /**
     * Denotes if this report PDF can display user submitted photos.
     *
     * @var bool
     */
    public $AllowsUserSubmittedPhotos;

    /**
     * List of delivery products.
     *
     * @var array<Product>
     */
    public $deliveryProducts;

    /**
     * List of delivery products.
     *
     * @var array<Product>
     */
    public $addOnProducts;

    /**
     * A list of measurement instructions options that have been parsed out with their
     * value, name and description just for you.
     *
     * @var array<MeasurementRequest>
     */
    public $measurementInstructionTypes;

    /**
     * Defines how to fill the "deliveryProducts" property on our Product class.
     *
     * @param array $deliveryProducts
     * @return void
     */
    protected function fillDeliveryProductsAttribute(array $deliveryProducts = []): void
    {
        $this->deliveryProducts = $this->transformCollection($deliveryProducts, Product::class);
    }

    /**
     * Defines how to fill the "addOnProducts" property on our Product class.
     *
     * @param array $addOnProducts
     * @return void
     */
    protected function fillAddOnProductsAttribute(array $addOnProducts = []): void
    {
        $this->addOnProducts = $this->transformCollection($addOnProducts, Product::class);
    }

    /**
     * Defines how to fill the "measurementInstructionTypes" property on our Product class.
     *
     * @param array<int> $ids
     * @return void
     */
    protected function fillMeasurementInstructionTypesAttribute(array $ids = []): void
    {
        $measurementRequests = [
            new PrimaryPlusDetachedGarage,
            new PrimaryStructureOnly,
            new AllStructuresOnParcel,
            new CommercialComplex,
            new Other,
        ];

        foreach ($ids as $id) {
            foreach ($measurementRequests as $measurementRequest) {
                if ($id == $measurementRequest->value) {
                    $this->measurementInstructionTypes[] = $measurementRequest;
                }
            }
        }
    }
}
