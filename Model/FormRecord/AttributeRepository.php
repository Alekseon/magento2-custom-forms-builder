<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Model\FormRecord;

/**
 * Class AttributeRepository
 * @package Alekseon\CustomFormsBuilder\Model\FormRecord
 */
class AttributeRepository extends \Alekseon\AlekseonEav\Model\AttributeRepository
{
    /**
     * @var AttributeFactory
     */
    protected $attributeFactory;

    /**
     * AttributeRepository constructor.
     * @param AttributeFactory $attributeFactory
     */
    public function __construct(
        \Alekseon\CustomFormsBuilder\Model\FormRecord\AttributeFactory $attributeFactory
    ) {
        $this->attributeFactory = $attributeFactory;
    }

    /**
     * @return AttributeFactory
     */
    public function getAttributeFactory()
    {
        return $this->attributeFactory;
    }
}
