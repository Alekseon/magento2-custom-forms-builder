<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord\Attribute;

/**
 * Class Collection
 * @package Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord\Attribute
 */
class Collection extends \Alekseon\AlekseonEav\Model\ResourceModel\Attribute\Collection
{
    /**
     * @return void
     */
    protected function _construct() // @codingStandardsIgnoreLine
    {
        $this->_init(
            'Alekseon\CustomFormsBuilder\Model\FormRecord\Attribute',
            'Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord\Attribute'
        );
    }
}
