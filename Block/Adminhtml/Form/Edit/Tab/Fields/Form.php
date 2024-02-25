<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Block\Adminhtml\Form\Edit\Tab\Fields;

use Alekseon\AlekseonEav\Model\Attribute\InputTypeRepository;
use Alekseon\CustomFormsBuilder\Model\FormRecord\Attribute;
use Magento\Framework\Data\Form\Element\Fieldset;

/**
 * Class Form
 * @package Alekseon\CustomFormsBuilder\Block\Adminhtml\Form\Edit\Tab\Fields
 */
class Form extends \Alekseon\AlekseonEav\Block\Adminhtml\Entity\Edit\Form
{
    /**
     * @var
     */
    protected $dataObject;
    /**
     * @var \Alekseon\AlekseonEav\Model\Adminhtml\System\Config\Source\InputType
     */
    protected $inputTypeSource;
    /**
     * @var \Magento\Config\Model\Config\Source\Yesno
     */
    protected $yesNoSource;
    /**
     * @var \Alekseon\CustomFormsBuilder\Model\FieldOptionSources
     */
    protected $fieldOptionSources;
    /**
     * @var \Magento\Framework\EntityManager\EventManager
     */
    protected $eventManager;
    /**
     * @var array
     */
    protected $formValues = [];

    /**
     * Form constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Alekseon\AlekseonEav\Model\Adminhtml\System\Config\Source\InputType $inputTypeSource
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Alekseon\AlekseonEav\Model\Adminhtml\System\Config\Source\InputType $inputTypeSource,
        \Magento\Config\Model\Config\Source\Yesno $yesNoSource,
        \Alekseon\CustomFormsBuilder\Model\FieldOptionSources $fieldOptionSources,
        \Magento\Framework\EntityManager\EventManager $eventManager,
        array $data = []
    ) {
        $this->inputTypeSource = $inputTypeSource;
        $this->yesNoSource = $yesNoSource;
        $this->fieldOptionSources = $fieldOptionSources;
        $this->eventManager = $eventManager;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return \Alekseon\CustomFormsBuilder\Model\Form
     */
    public function getDataObject()
    {
        if (null === $this->dataObject) {
            return $this->_coreRegistry->registry('current_form');
        }
        return $this->dataObject;
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $this->addCurrentFormFields($form);
        $this->addFieldFieldset($form, 'new_field', [
            'title' => __('New Field'),
            'is_new_field' => true,
        ]);

        $form->addField('form_removed_fields', 'hidden',
            [
                'name' => 'form_removed_fields'
            ]
        );

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @param \Magento\Framework\Data\Form $form
     * @param string $formFieldId
     * @param array $settings
     */
    protected function addFieldFieldset(\Magento\Framework\Data\Form $form, string $formFieldId, array $settings = [])
    {
        $isNewField = (bool) ($settings['is_new_field'] ?? false);
        $attribute = $settings['attribute'] ?? false;

        $fieldset = $form->addFieldset('form_field_' . $formFieldId,
            [
                'legend' => $settings['title'] ?? 'no title',
                'collapsable' => true,
                'header_bar' => $this->getIdentifierBlockHtml($formFieldId, $settings),
                'class' => $isNewField ? '' : 'form_tab_' . ($settings['tab_id'] ?? ''),
            ]
        );

        $warningsHtml = $this->getFieldWarningsHtml($settings);
        if ($warningsHtml) {
            $fieldset->addField('form_field_' . $formFieldId . '_warning', 'note',
                [
                    'text' => $warningsHtml
                ]
            );
        }

        $fieldset->addField('form_field_' . $formFieldId . '_id', 'hidden',
            [
                'name' => 'form_fields[' . $formFieldId . '][id]'
            ]
        )->addCustomAttribute("data-fieldcode", "id");

        $fieldset->addField('form_field_' . $formFieldId . '_group_code', 'hidden',
            [
                'name' => 'form_fields[' . $formFieldId . '][group_code]',
                'class' => 'group-code',
            ]
        )->addCustomAttribute("data-fieldcode", "group_code");

        $fieldset->addField('form_field_' . $formFieldId . '_frontend_label', 'text',
            [
                'label' => __('Field Label'),
                'name' => 'form_fields[' . $formFieldId . '][frontend_label]'
            ]
        )->addCustomAttribute("data-fieldcode", "frontend_label");

        if ($isNewField) {
            $fieldset->addField('form_field_' . $formFieldId . '_frontend_input', 'select',
                [
                    'label' => __('Frontend Input'),
                    'name' => 'form_fields[' . $formFieldId . '][frontend_input]',
                    'value' => InputTypeRepository::DEFAULT_INPUT_TYPE_CODE,
                    'values' => $this->inputTypeSource->toOptionArray()
                ]
            )->addCustomAttribute("data-fieldcode", "frontend_input");
        }

        $fieldset->addField('form_field_' . $formFieldId . '_input_visibility', 'select',
            [
                'label' => __('Input Visibility'),
                'name' => 'form_fields[' . $formFieldId . '][input_visibility]',
                'values' => $this->getDataObject()->getFieldsCollection()->getFirstItem()->getInputVisibilityOptions(),
                'value' => $isNewField ? Attribute::INPUT_VISIBILITY_VISIBILE : $attribute->getInputVisibility(),
            ]
        )->addCustomAttribute("data-fieldcode", "input_visibility");

        $fieldset->addField('form_field_' . $formFieldId . '_is_required', 'select',
            [
                'label' => __('Is Required'),
                'name' => 'form_fields[' . $formFieldId . '][is_required]',
                'values' => $this->yesNoSource->toOptionArray()
            ]
        )->addCustomAttribute("data-fieldcode", "is_required");

        if ($this->canSelectOptionSource($attribute)) {
            $fieldset->addField('form_field_' . $formFieldId . '_option_source_code', 'select',
                [
                    'label' => __('Options Source'),
                    'name' => 'form_fields[' . $formFieldId . '][option_source_code]',
                    'values' => $this->fieldOptionSources->toOptionArray(),
                    'note' => __('WARNING: Since Form has records, this option should NOT be changed, as it can iterrupt saved data.'),
                ]
            )->addCustomAttribute("data-fieldcode", "option_source_code");
        }

        $fieldset->addField('form_field_' . $formFieldId . '_sort_order', 'text',
            [
                'label' => __('Sort Order'),
                'name' => 'form_fields[' . $formFieldId . '][sort_order]'
            ]
        )->addCustomAttribute("data-fieldcode", "sort_order");

        $this->eventManager->dispatch(
            'alekseon_custom_form_after_add_field_fieldset',
            [
                'form_field_id' => $formFieldId,
                'form' => $form,
                'fieldset' => $fieldset,
                'field_settings' => $settings,
            ]
        );

        $this->addInputParamsField($fieldset, $formFieldId, $settings);
        $this->addActionLinks($fieldset, $formFieldId, $settings);
    }

    /**
     * @param string $formFieldId
     * @param array $settings
     * @return string
     */
    private function getIdentifierBlockHtml(string $formFieldId, array $settings)
    {
        /** @var \Magento\Backend\Block\Template $identifierBlock */
        $identifierBlock = $this->_layout->createBlock(\Magento\Backend\Block\Template::class)
            ->setSettings($settings)
            ->setFormFieldId($formFieldId)
            ->setTemplate('Alekseon_CustomFormsBuilder::form/edit/field/identifier.phtml');

        return $identifierBlock->toHtml();
    }

    /**
     * @param Fieldset $fieldset
     * @param string $formFieldId
     * @param array $settings
     * @return void
     */
    private function addInputParamsField(Fieldset $fieldset, string $formFieldId, array $settings)
    {
        $inputParams = $this->getInputParams($settings);
        foreach ($inputParams as $paramCode => $paramConfig) {
            $fieldset->addField('form_field_' . $formFieldId . '_input_params_' . $paramCode, 'text',
                [
                    'label' => __($paramConfig['label']),
                    'name' => 'form_fields[' . $formFieldId . '][input_params][' . $paramCode . ']',
                    'note' => isset($paramConfig['note']) ? __($paramConfig['note']) : '',
                ]
            );
        }
    }

    /**
     * @param Fieldset $fieldset
     * @param string $formFieldId
     * @param array $settings
     * @return void
     */
    private function addActionLinks(Fieldset $fieldset, string $formFieldId, array $settings)
    {
        $isNewField = (bool) ($settings['is_new_field'] ?? false);
        $actionLinks = [];
        if (!$isNewField) {
            $actionLinks[] = '<a href="'
                . $this->getUrl('*/formField/edit', ['id' => $formFieldId])
                . '" target="_blank" class="edit-field-button">'
                . __('Advanced Settings')
                . '</a>';
        }

        $actionLinks[] = '<a class="form-field-change-tab-button" href="">' . __('Change tab')
            . '</a><select class="change-tab-select" style="display: none"></select>';
        $actionLinks[] = '<a href="#" class="delete-field-button">' . __('Delete') . '</a>';

        $fieldset->addField('form_field_' . $formFieldId . '_action', 'note',
            [
                'text' => implode(' | ', $actionLinks)
            ]
        );
    }

    /**
     * @param $settings
     * @return array
     */
    protected function getInputParams($settings)
    {
        if (isset($settings['attribute'])) {
            $attribute = $settings['attribute'];
            return $attribute->getInputParamsConfig();
        }

        return [];
    }

    /**
     * @param $settings
     * @return string
     */
    protected function getFieldWarningsHtml($settings)
    {
        $html = '';
        if (isset($settings['warnings']) && is_array($settings['warnings'])) {
            $warnings = $settings['warnings'];
            foreach ($warnings as $warning) {
                $html .= '<div class="message">' . $warning . '</div>';
            }
        }
        return $html;
    }

    /**
     * @param \Alekseon\CustomFormsBuilder\Model\Form\Attribute $attribute
     * @return array
     */
    public function getFieldSettings($attribute)
    {
        $frontendInputs = $this->inputTypeSource->getOptionArray();
        if (isset($frontendInputs[$attribute->getFrontendInput()])) {
            $frontendInputLabel = $frontendInputs[$attribute->getFrontendInput()];
        } else {
            $frontendInputLabel = __('Unknown') . ':' . $attribute->getFrontendInput();
        }

        if ($attribute->getIdentifier()) {
            $identifier = $attribute->getIdentifier();
        } else {
            $codePrefix = 'field_' . $this->getDataObject()->getId() . '_';
            $identifier = substr($attribute->getAttributeCode(), strlen($codePrefix));
        }

        $formFieldSettings = [
            'title' => '[' . $frontendInputLabel . '] ' . $attribute->getDefaultFrontendLabel(),
            'attribute' => $attribute,
            'identifier' => $identifier,
            'tab_id' => $this->getFieldTabId($attribute)
        ];

        return $formFieldSettings;
    }

    /**
     * @param $field
     * @return int
     */
    protected function getFieldTabId($field)
    {
        $formTabs = $this->getDataObject()->getFormTabs();
        $fieldTabId = $field->getGroupCode();
        if (!isset($formTabs[$fieldTabId])) {
            $fieldTabId = $this->getDataObject()->getFirstFormTab()->getId();
        }
        return $fieldTabId;
    }

    /**
     * @param $attribute
     */
    protected function canSelectOptionSource($attribute)
    {
        if (!$attribute || !$attribute->usesSource()) {
            return false;
        }

        $inputTypeModel = $attribute->getInputTypeModel();
        if ($inputTypeModel->hasCustomOptionSource()) {
            return true;
        }

        return false;
    }

    /**
     * @param $form
     * @param $dataObject
     */
    protected function addCurrentFormFields($form)
    {
        $recordAttributeCollection = $this->getDataObject()->getFieldsCollection(true);
        foreach ($recordAttributeCollection as $attribute) {
            $formFieldSettings = $this->getFieldSettings($attribute);
            $this->addFieldFieldset($form,  $attribute->getId(), $formFieldSettings);

            $optionSourceCode = $this->fieldOptionSources->getCodeBySourceModel($attribute->getData('source_model'));
            if ($optionSourceCode) {
                $attribute->setOptionSourceCode($optionSourceCode);
            }

            $attributeData = $attribute->getData();
            foreach ($attributeData as $dataId => $dataValue) {
                $this->formValues['form_field_' . $attribute->getId() . '_' . $dataId] = $dataValue;
            }

            $this->formValues['form_field_' . $attribute->getId() . '_' . 'group_code'] = $formFieldSettings['tab_id'] ?? '';

            $settings = [
                'attribute' => $attribute
            ];

            $inputParams = $this->getInputParams($settings);
            foreach (array_keys($inputParams) as $paramCode) {
                $value = $attribute->getInputParam($paramCode);
                if ($value !== '') {
                    $this->formValues['form_field_' . $attribute->getId() . '_input_params_' . $paramCode] = $value;
                }
            }
        }
    }

    /**
     * @return \Alekseon\AlekseonEav\Block\Adminhtml\Entity\Edit\Form
     */
    protected function _initFormValues()
    {
        $this->getForm()->addValues($this->formValues);
        return parent::_initFormValues();
    }
}
