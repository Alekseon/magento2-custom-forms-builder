<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Model\FormRecord;

use Alekseon\CustomFormsBuilder\Model\FieldOptionSources;
use Alekseon\CustomFormsBuilder\Model\Form;
use Alekseon\CustomFormsBuilder\Model\FormRepository;

/**
 * Class Attribute
 * @package Alekseon\CustomFormsBuilder\Model\FormRecord
 */
class Attribute extends \Alekseon\AlekseonEav\Model\Attribute
{
    /**
     * @var
     */
    protected $_eventPrefix = 'alekseon_custom_form_record_attibute';
    protected $_eventObject = 'attribute';
    /**
     * @var \Alekseon\CustomFormsBuilder\Model\FieldOptionSources
     */
    protected $fieldOptionSources;
    /**
     * @var
     */
    protected $form;


    /**
     * Attribute constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Alekseon\AlekseonEav\Model\Attribute\InputTypeRepository $inputTypeRepository
     * @param \Alekseon\AlekseonEav\Model\Attribute\InputValidatorRepository $inputValidatorRepository
     * @param \Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord\Attribute $resource
     * @param \Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord\Attribute\Collection $resourceCollection
     * @param \Alekseon\CustomFormsBuilder\Model\FieldOptionSources $fieldOptionSources
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Alekseon\AlekseonEav\Model\Attribute\InputTypeRepository $inputTypeRepository,
        \Alekseon\AlekseonEav\Model\Attribute\InputValidatorRepository $inputValidatorRepository,
        \Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord\Attribute $resource,
        \Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord\Attribute\Collection $resourceCollection,
        FieldOptionSources $fieldOptionSources,
        FormRepository $formRepository
    ) {
        $this->fieldOptionSources = $fieldOptionSources;
        $this->formRepository = $formRepository;
        parent::__construct(
            $context, $registry, $inputTypeRepository, $inputValidatorRepository, $resource, $resourceCollection
        );
    }

    /**
     * @return \Alekseon\AlekseonEav\Model\Attribute
     */
    public function beforeSave()
    {
        if ($optionSourceCode = $this->getOptionSourceCode()) {

            $optionSource = $this->fieldOptionSources->getOptionSourceByCode($optionSourceCode);
            $backendType = $this->getInputTypeModel()->getDefaultBackendType();

            if ($optionSource) {
                $this->setSourceModel($optionSource->getSourceModel());
                if ($optionSource->getBackendType()) {
                    $backendType = $optionSource->getBackendType();
                }
                $this->setBackendType($backendType);
            }

            if ($optionSourceCode == FieldOptionSources::DEFAULT_ATTRIBUTE_SOURCE_VALUE) {
                $this->setSourceModel(null);
                $this->setBackendType($backendType);
            }
        }

        $this->setInputParams($this->getInputParams());

        return parent::beforeSave();
    }


    /**
     * @param $form
     * @return \Alekseon\CustomFormsBuilder\Model\Form\Attribute
     */
    public function setForm(Form $form)
    {
        $this->form = $form;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getForm(): Form
    {
        if ($this->form === null) {
            $this->form = $this->formRepository->getById($this->getFormId());
        }

        return $this->form;
    }
}
