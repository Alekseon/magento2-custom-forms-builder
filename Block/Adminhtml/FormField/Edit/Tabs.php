<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Block\Adminhtml\FormField\Edit;

/**
 * Class Tabs
 * @package Alekseon\CustomFormsBuilder\Block\Adminhtml\FormField\Edit
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('attribute_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Form Field Information'));
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'main',
            [
                'label' => __('Properties'),
                'title' => __('Properties'),
                'content' => $this->getChildHtml('general'),
                'active' => true
            ]
        );

        $this->addTab(
            'frontend_labels',
            [
                'label' => __('Frontend Labels'),
                'title' => __('Frontend Labels'),
                'content' => $this->getChildHtml('frontend_labels'),
            ]
        );

        return parent::_beforeToHtml();
    }
}
