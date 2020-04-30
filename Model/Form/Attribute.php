<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Model\Form;

/**
 * Class Attribute
 * @package Alekseon\CustomFormsBuilder\Model\Form
 */
class Attribute extends \Alekseon\AlekseonEav\Model\Attribute
{
    /**
     * @var bool
     */
    protected $canUseGroup = true;

    /**
     * Attribute constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Alekseon\AlekseonEav\Model\Attribute\InputTypeRepository $inputTypeRepository
     * @param \Alekseon\AlekseonEav\Model\Attribute\InputValidatorRepository $inputValidatorRepository
     * @param \Alekseon\CustomFormsBuilder\Model\ResourceModel\Form\Attribute $resource
     * @param \Alekseon\CustomFormsBuilder\Model\ResourceModel\Form\Attribute\Collection $resourceCollection
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Alekseon\AlekseonEav\Model\Attribute\InputTypeRepository $inputTypeRepository,
        \Alekseon\AlekseonEav\Model\Attribute\InputValidatorRepository $inputValidatorRepository,
        \Alekseon\CustomFormsBuilder\Model\ResourceModel\Form\Attribute $resource,
        \Alekseon\CustomFormsBuilder\Model\ResourceModel\Form\Attribute\Collection $resourceCollection
    ) {
        parent::__construct(
            $context, $registry, $inputTypeRepository, $inputValidatorRepository, $resource, $resourceCollection
        );
    }
}
