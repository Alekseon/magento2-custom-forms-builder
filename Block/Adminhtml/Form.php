<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Block\Adminhtml;

/**
 * Class Form
 * @package Alekseon\CustomFormsBuilder\Block\Adminhtml
 */
class Form extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_form';
        $this->_blockGroup = 'Alekseon_CustomFormsBuilder';
        $this->_headerText = __('Custom Forms');
        $this->_addButtonLabel = __('Add New Form');
        parent::_construct();
    }
}
