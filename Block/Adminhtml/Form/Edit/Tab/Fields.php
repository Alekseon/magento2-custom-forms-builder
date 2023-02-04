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
    const DEFAULT_FORM_TAB_LABEL = 'General';
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry
    ) {
        $this->registry = $registry;
        parent::__construct($context);
    }

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

    /**
     * @return mixed|null
     */
    public function getCurrentForm()
    {
        return $this->registry->registry('current_form');
    }

    /**
     * @return array[]
     */
    public function getFormTabs()
    {
        return $this->getCurrentForm()->getFormTabs();
    }

    /**
     * @return mixed
     */
    public function getActiveFormTab()
    {
        return $this->getCurrentForm()->getFirstFormTab();
    }

    /**
     * @return void
     */
    public function getLastTabNumber()
    {
        $lastTabNumber = 1;
        $tabs = $this->getFormTabs();
        foreach ($tabs as $tab) {
            $tabNumber = (int) $tab->getCode();
            if ($tabNumber > $lastTabNumber) {
                $lastTabNumber = $tabNumber;
            }
        }

        return $lastTabNumber;
    }
}
