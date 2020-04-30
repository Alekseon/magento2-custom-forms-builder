<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */

namespace Alekseon\CustomFormsBuilder\Block\Adminhtml\FormField;

/**
 * Class Edit
 * @package Alekseon\Storelocator\Block\Adminhtml\Store\Attribute
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Block group name
     *
     * @var string
     */
    protected $_blockGroup = 'Alekseon_CustomFormsBuilder';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'attribute_code';
        $this->_controller = 'adminhtml_formField';

        parent::_construct();

        $this->removeButton('back');
    }
}
