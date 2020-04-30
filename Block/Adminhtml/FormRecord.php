<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Block\Adminhtml;

/**
 * Class FormRecord
 * @package Alekseon\CustomFormsBuilder\Block\Adminhtml
 */
class FormRecord extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * FormRecord constructor.
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_formRecord';
        $this->_blockGroup = 'Alekseon_CustomFormsBuilder';
        $this->_headerText = __('Custom Form Records');
        $this->_addButtonLabel = __('Add New Record');
        parent::_construct();
    }

    /**
     * @return string
     */
    public function getCreateUrl()
    {
        return $this->getUrl('*/*/new', ['form_id' => $this->getCurrentForm()->getId()]);
    }

    /**
     * @return mixed
     */
    protected function getCurrentForm()
    {
        return $this->coreRegistry->registry('current_form');
    }
}
