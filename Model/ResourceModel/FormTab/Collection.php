<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Model\ResourceModel\FormTab;

use Alekseon\CustomFormsBuilder\Model\Form;

/**
 * Class Collection
 * @package Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @return void
     */
    protected function _construct() // @codingStandardsIgnoreLine
    {
        $this->_init(
            'Alekseon\CustomFormsBuilder\Model\FormTab',
            'Alekseon\CustomFormsBuilder\Model\ResourceModel\FormTab'
        );
    }

    /**
     * @param Form $form
     * @return $this
     */
    public function addFormFilter(Form $form)
    {
        $this->addFieldToFilter('form_id', $form->getId());
        return $this;
    }
}
