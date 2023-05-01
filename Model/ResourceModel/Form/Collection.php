<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Model\ResourceModel\Form;

/**
 * Class Collection
 * @package Alekseon\CustomFormsBuilder\Model\ResourceModel\Form
 */
class Collection extends \Alekseon\AlekseonEav\Model\ResourceModel\Entity\Collection
{
    /**
     * @return void
     */
    protected function _construct() // @codingStandardsIgnoreLine
    {
        $this->_init(
            'Alekseon\CustomFormsBuilder\Model\Form',
            'Alekseon\CustomFormsBuilder\Model\ResourceModel\Form'
        );
    }
}
