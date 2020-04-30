<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Model\FormRecord;

/**
 * Class Attribute
 * @package Alekseon\CustomFormsBuilder\Model\FormRecord
 */
class Attribute extends \Alekseon\AlekseonEav\Model\Attribute
{
    /**
     * Attribute constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Alekseon\AlekseonEav\Model\Attribute\InputTypeRepository $inputTypeRepository
     * @param \Alekseon\AlekseonEav\Model\Attribute\InputValidatorRepository $inputValidatorRepository
     * @param \Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord\Attribute $resource
     * @param \Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord\Attribute\Collection $resourceCollection
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Alekseon\AlekseonEav\Model\Attribute\InputTypeRepository $inputTypeRepository,
        \Alekseon\AlekseonEav\Model\Attribute\InputValidatorRepository $inputValidatorRepository,
        \Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord\Attribute $resource,
        \Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord\Attribute\Collection $resourceCollection
    ) {
        parent::__construct(
            $context, $registry, $inputTypeRepository, $inputValidatorRepository, $resource, $resourceCollection
        );
    }
}
