<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord;

/**
 * Class Collection
 * @package Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord
 */
class Collection extends \Alekseon\AlekseonEav\Model\ResourceModel\Entity\Collection
{
    /**
     * @return void
     */
    protected function _construct() // @codingStandardsIgnoreLine
    {
        $this->_init(
            'Alekseon\CustomFormsBuilder\Model\FormRecord',
            'Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord'
        );
    }
}
