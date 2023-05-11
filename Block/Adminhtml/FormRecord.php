<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

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

        if (!$this->getCurrentForm()->getManageFormAllowedDisallowedFlag()) {
            $this->addButton(
                'manage_form',
                [
                    'label' => __('Manage Form'),
                    'onclick' => 'setLocation(\'' . $this->getManageFormUrl() . '\')',
                ]
            );
        }

        if (!$this->isAddNewRecordAllowed()) {
            $this->removeButton('add');
        }
    }

    /**
     * @return string
     */
    public function getCreateUrl()
    {
        return $this->getUrl('*/*/new', ['form_id' => $this->getCurrentForm()->getId()]);
    }

    /**
     * @return string
     */
    private function getManageFormUrl()
    {
        return $this->getUrl('*/form/edit', [
            'entity_id' => $this->getCurrentForm()->getId(),
            'back_to_records' => true,
        ]);
    }

    /**
     * @return mixed
     */
    public function getCurrentForm()
    {
        return $this->coreRegistry->registry('current_form');
    }

    /**
     *
     */
    protected function isAddNewRecordAllowed()
    {
        $manageResource = 'Alekseon_CustomFormsBuilder::manage_custom_forms';
        if ($this->_authorization->isAllowed($manageResource)) {
            return true;
        }

        $resource = 'Alekseon_CustomFormsBuilder::custom_form_' . $this->getCurrentForm()->getId() . '_save';
        return $this->_authorization->isAllowed($resource);
    }
}
