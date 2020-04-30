<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Model\ResourceModel\Form\Attribute;

/**
 * Class Collection
 * @package Alekseon\CustomFormsBuilder\Model\ResourceModel\Form\Attribute
 */
class Collection extends \Alekseon\AlekseonEav\Model\ResourceModel\Attribute\Collection
{
    /**
     * @return void
     */
    protected function _construct() // @codingStandardsIgnoreLine
    {
        $this->_init(
            'Alekseon\CustomFormsBuilder\Model\Form\Attribute',
            'Alekseon\CustomFormsBuilder\Model\ResourceModel\Form\Attribute'
        );
    }
}
