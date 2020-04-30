<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Model;

/**
 * Class FormRecord
 * @package Alekseon\CustomFormsBuilder\Model
 */
class FormRecord extends \Alekseon\AlekseonEav\Model\Entity
{
    /**
     * FormRecord constructor.
     * @param \Alekseon\AlekseonEav\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ResourceModel\FormRecord $resource
     * @param ResourceModel\FormRecord\Collection $resourceCollection
     */
    public function __construct(
        \Alekseon\AlekseonEav\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord $resource,
        \Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord\Collection $resourceCollection
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection
        );
    }
}
