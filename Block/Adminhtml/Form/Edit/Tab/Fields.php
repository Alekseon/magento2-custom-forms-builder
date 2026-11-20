<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Block\Adminhtml\Form\Edit\Tab;

use Alekseon\AlekseonEav\Model\Attribute\InputTypeRepository;

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
     * @var InputTypeRepository
     */
    protected $inputTypeRepository;
    /**
     * @var
     */
    protected $lastTab;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        ?InputTypeRepository $inputTypeRepository = null
    ) {
        $this->registry = $registry;
        $this->inputTypeRepository = $inputTypeRepository
            ?? \Magento\Framework\App\ObjectManager::getInstance()->get(InputTypeRepository::class);
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
        $options = [];

        foreach ($this->inputTypeRepository->getFrontendInputTypes() as $code => $inputType) {
            $options[] = ['id' => $code, 'label' => __($inputType->getLabel())];
        }

        $this->addChild(
            'add_new_field_button',
            \Magento\Backend\Block\Widget\Button\SplitButton::class,
            [
                'label' => __('Add Element'),
                'data_attribute' => ['mage-init' => '{"dropdown": {}}', 'toggle' => 'dropdown'],
                'class' => 'add',
                'id' => 'add_field_button',
                'options' => $options,
            ]
        );

        return parent::_prepareLayout();
    }

    /**
     * @return array
     */
    public function getNewFieldTemplatesJsConfig(): array
    {
        $config = [];
        foreach ($this->inputTypeRepository->getFrontendInputTypes() as $inputType) {
            $fieldsetType = (string) $inputType->getFieldsetType();
            if (!$fieldsetType) {
                $fieldsetType = 'default';
            }
            $config[] = [
                'buttonId' => 'add_field_button-' . $inputType->getCode(),
                'templateId' => 'form_field_new_field_' . $fieldsetType .  '-wrapper',
                'type' => $inputType->getCode(),
                'label' => (string) __($inputType->getLabel()),
            ];
        }
        return $config;
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
        $jsonHelper = $this->getData('jsonHelper');
        $formTabs = $this->getCurrentForm()->getFormTabs();
        $tabsData  = [];
        foreach ($formTabs as $tab) {
            $tabsData[] = [
                'label' => $tab->getLabel(),
                'code' => $tab->getId()
            ];
            $this->lastTab = $tab;
        }
        return $jsonHelper->jsonEncode($tabsData);
    }

    /**
     * @return mixed
     */
    public function getActiveFormTab()
    {
        return $this->getCurrentForm()->getFirstFormTab();
    }

    /**
     * @return int | null
     */
    public function getLastTabId()
    {
        return $this->lastTab->getId();
    }
}
