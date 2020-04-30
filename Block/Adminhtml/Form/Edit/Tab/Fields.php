<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Block\Adminhtml\Form\Edit\Tab;

/**
 * Class Fields
 * @package Alekseon\CustomFormsBuilder\Block\Adminhtml\Form\Edit\Tab
 */
class Fields extends \Magento\Backend\Block\Template implements
    \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Form Fields');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Form Fields');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }


    /**
     * @return \Magento\Backend\Block\Template
     */
    protected function _prepareLayout()
    {
        $this->addChild(
            'add_new_field_button',
            \Magento\Backend\Block\Widget\Button::class,
            [
                'label' => __('Add Field'),
                'data_attribute' => ['action' => 'add-form-field'],
                'class' => 'add',
                'id' => 'add_field_button',
            ]
        );

        return parent::_prepareLayout();
    }
}