<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject;

/**
 * Class Form
 * @package Alekseon\CustomFormsBuilder\Model
 */
class Form extends \Alekseon\AlekseonEav\Model\Entity
{
    const DEFAULT_FORM_TAB_LABEL = 'General';

    /**
     * @var FormRecord\AttributeRepository
     */
    protected $recordAttributeRepository;
    /**
     * @var
     */
    protected $formTabs;
    /**
     * @var
     */
    protected $firstFormTab;

    /**
     * Form constructor.
     * @param \Alekseon\AlekseonEav\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ResourceModel\Form $resource
     * @param ResourceModel\Form\Collection $resourceCollection
     * @param FormRecord\AttributeRepository $recordAttributeRepository
     */
    public function __construct(
        \Alekseon\AlekseonEav\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Alekseon\CustomFormsBuilder\Model\ResourceModel\Form $resource,
        \Alekseon\CustomFormsBuilder\Model\ResourceModel\Form\Collection $resourceCollection,
        \Alekseon\CustomFormsBuilder\Model\FormRecord\AttributeRepository $recordAttributeRepository
    ) {
        $this->recordAttributeRepository = $recordAttributeRepository;
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection
        );
    }

    /**
     * @return Form
     */
    public function beforeSave()
    {
        if ($this->getData('new_form_tab')) {
            $newFormTabs = $this->getData('new_form_tab');
            if (is_array($newFormTabs)) {
                $formTabs = $this->getData('form_tabs');
                foreach ($newFormTabs as $tabKey => $tabData) {
                    $formTabs[$tabKey] = [
                        'label' => $tabData['label'],
                    ];
                }
                $this->setData('form_tabs', $formTabs);
            }
        }
        return parent::beforeSave();
    }

    /**
     * @return \Alekseon\AlekseonEav\Model\Entity
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\TemporaryState\CouldNotSaveException
     */
    public function afterSave()
    {
        $this->updateFields();
        $this->addNewFields();

        return parent::afterSave();
    }

    /**
     * @return bool
     */
    protected function addNewFields()
    {
        $formId = $this->getId();

        $newFieldsData = $this->getNewFields();

        if (!$formId || !is_array($newFieldsData)) {
            return false;
        }

        $attributeFactory = $this->recordAttributeRepository->getAttributeFactory();
        foreach ($newFieldsData as $id => $newFieldData) {
            if (isset($newFieldData['frontend_label']) && $newFieldData['frontend_label']) {
                unset($newFieldData['id']);
                $attribute = $attributeFactory->create();
                $attribute->setData($newFieldData);
                $attribute->setVisibleInGrid(true);
                $attribute->setAttributeCode('field_' . $formId . '_' . time() . '_' . $id);
                $attribute->setFormId($formId);
                $this->recordAttributeRepository->save($attribute);
            }
        }
    }

    /**
     *
     */
    protected function updateFields()
    {
        $removedFields = explode(',', $this->getFormRemovedFields());
        $formFields = $this->getFormFields();

        foreach ($removedFields as $attributeId) {
            if ($attributeId) {
                $attribute = $this->recordAttributeRepository->getById($attributeId);
                $attribute->delete();
            }
        }

        if (!is_array($formFields)) {
            return;
        }

        foreach ($formFields as $fieldData) {
            $attributeId = isset($fieldData['id']) ? (int) $fieldData['id'] : false;
            if (!$attributeId) {
                continue;
            }

            if (in_array($attributeId, $removedFields)) {
                continue;
            }

            $attribute = $this->recordAttributeRepository->getById($attributeId);
            unset($fieldData['frontend_input']);
            $attribute->addData($fieldData);
            $this->recordAttributeRepository->save($attribute);
        }
    }

    /**
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function getFieldsCollection()
    {
        $attributeObject = $this->recordAttributeRepository->getAttributeFactory()->create();
        $recordAttributeCollection = $attributeObject->getCollection();
        $recordAttributeCollection->addFieldToFilter('form_id', $this->getId());
        $recordAttributeCollection->setOrder('sort_order', AbstractDb::SORT_ORDER_ASC);
        return $recordAttributeCollection;
    }

    /**
     * @return void
     */
    public function getFormTabs()
    {
        if ($this->formTabs === null) {
            $formTabs = $this->getData('form_tabs');
            if (!$formTabs) {
                $formTabs = [
                    1 => [
                        'label' => __(self::DEFAULT_FORM_TAB_LABEL),
                    ]
                ];
            }

            $this->formTabs = [];

            foreach ($formTabs as $tabCode => $tabData) {
                $tab = [
                    'label' => $tabData['label'] ?? __('New Tab'),
                    'code' => $tabCode,
                ];

                if (empty($this->formTabs)) {
                    $this->firstFormTab = $tab;
                }
                $this->formTabs[$tabCode] = $tab;
            }
        }

        return $this->formTabs;
    }

    /**
     * @return mixed
     */
    public function getFirstFormTab()
    {
        $this->getFormTabs();
        return $this->firstFormTab;
    }
}
