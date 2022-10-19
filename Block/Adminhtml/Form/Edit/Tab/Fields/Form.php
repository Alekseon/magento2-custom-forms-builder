<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Block\Adminhtml\Form\Edit\Tab\Fields;

use Alekseon\AlekseonEav\Model\Attribute\InputType\Boolean;
use Alekseon\AlekseonEav\Model\Attribute\InputTypeRepository;
use Magento\Framework\Data\Collection\AbstractDb;

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
     * @return mixed
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
     * @param array $settings
     */
    protected function addFieldFieldset($form, $formFieldId, $settings = [])
    {
        if (isset($settings['is_new_field']) && $settings['is_new_field']) {
            $isNewField = true;
        } else {
            $isNewField = false;
        }


        $fieldset = $form->addFieldset('form_field_' . $formFieldId,
            [
                'legend' => $settings['title'] ?? 'no title',
                'collapsable' => true,
                'header_bar' => isset($settings['identifier']) ? __('Identifier') . ': ' . $settings['identifier'] : '',
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
        );

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

        $fieldset->addField('form_field_' . $formFieldId . '_is_required', 'select',
            [
                'label' => __('Is Required'),
                'name' => 'form_fields[' . $formFieldId . '][is_required]',
                'values' => $this->yesNoSource->toOptionArray()
            ]
        )->addCustomAttribute("data-fieldcode", "is_required");

        if (isset($settings['attribute']) && $this->canSelectOptionSource($settings['attribute'])) {
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

        $inputParams = $this->getInputParams($settings);
        foreach ($inputParams as $paramCode => $paramConfig) {
            $fieldset->addField('form_field_' . $formFieldId . '_input_params_' . $paramCode, 'text',
                [
                    'label' => __($paramConfig['label']),
                    'name' => 'form_fields[' . $formFieldId . '][input_params][' . $paramCode . ']'
                ]
            );
        }

        $actionLinks = [];
        if (!$isNewField) {
            $actionLinks[] = '<a href="'
                . $this->getUrl('*/formField/edit', ['id' => $formFieldId])
                . '" target="_blank" class="edit-field-button">'
                . __('Advanced Settings')
                . '</a>';
        }
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
            if ($attribute->getCanUseInputParams()) {
                $inputTypeConfig = $attribute->getFrontendInputTypeConfig();
                $params = $inputTypeConfig->getInputParams();
                if ($params) {
                    return $params;
                }
            }
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
     * @param $attribute
     * @return array
     */
    public function getFieldSettings($attribute)
    {
        $frontendInputs = $this->inputTypeSource->getOptionArray();
        if (isset($frontendInputs[$attribute->getFrontendInput()])) {
            $frontendInputLabel = $frontendInputs[$attribute->getFrontendInput()];
        } else {
            $frontendInputLabel = $attribute->getFrontendInput();
        }

        if ($attribute->getIdentifier()) {
            $identifier = $attribute->getIdentifier();
        } else {
            $codePrefix = 'field_' . $this->getDataObject()->getId() . '_';
            $identifier = substr($attribute->getAttributeCode(), strlen($codePrefix));
        }

        $formFieldSettings = [
            'title' => '[' . $frontendInputLabel . '] ' . $attribute->getFrontendLabel(),
            'attribute' => $attribute,
            'identifier' => $identifier,
        ];

        return $formFieldSettings;
    }

    /**
     * @param $attribute
     */
    protected function canSelectOptionSource($attribute)
    {
        if (!$attribute->usesSource()) {
            return false;
        }

        $inputTypeModel = $attribute->getInputTypeModel();
        if ($inputTypeModel instanceof Boolean) {
            return false;
        }

        return true;
    }

    /**
     * @param $form
     * @param $dataObject
     */
    protected function addCurrentFormFields($form)
    {
        $recordAttributeCollection = $this->getDataObject()->getFieldsCollection();
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

            $settings = [
                'attribute' => $attribute
            ];

            $inputParams = $this->getInputParams($settings);
            foreach ($inputParams as $paramCode => $paramConfig) {
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
