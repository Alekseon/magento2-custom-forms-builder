<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Model;

use Alekseon\CustomFormsBuilder\Model\FormRecord\Attribute;
use Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord\Collection;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class Form
 * @package Alekseon\CustomFormsBuilder\Model
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 *
 * @method bool getCanUseForWidget()
 * @method string|null  getTitle()
 * @method string|null getFrontendFormDescription()
 * @method string|null getIdentifier()
 * @method bool getEnableMultipleSteps()
 */
class Form extends \Alekseon\AlekseonEav\Model\Entity implements IdentityInterface
{
    const DEFAULT_FORM_TAB_LABEL = 'General';
    const GROUP_FEILDS_IN_FIELDSETS_OPTION = 'fieldsets';
    const GROUP_FIELDS_IN_TABS_OPTION = 'tabs';

    const CACHE_TAG = 'alekseon_custom_form';

    /**
     * @var FormRecord\AttributeRepository
     */
    protected $recordAttributeRepository;
    /**
     * @var FormTabFactory
     */
    protected $formTabFactory;
    /**
     * @var
     */
    protected $formTabs;
    /**
     * @var FormRecordFactory
     */
    protected $formRecordFactory;
    /**
     * @var ResourceModel\FormRecord\Attribute\Collection
     */
    private $fieldsCollection;
    /**
     * @var array|null
     */
    private $fieldsIdentifierMap;

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
        \Alekseon\CustomFormsBuilder\Model\FormRecord\AttributeRepository $recordAttributeRepository,
        \Alekseon\CustomFormsBuilder\Model\FormTabFactory $formTabFactory,
        \Alekseon\CustomFormsBuilder\Model\FormRecordFactory $formRecordFactory
    ) {
        $this->recordAttributeRepository = $recordAttributeRepository;
        $this->formTabFactory = $formTabFactory;
        $this->formRecordFactory = $formRecordFactory;
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection
        );
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
     * @return void
     */
    private function addNewFields()
    {
        $formId = $this->getId();

        $newFieldsData = $this->getNewFields();

        if (!$formId || !is_array($newFieldsData)) {
            return;
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
                $this->updateFieldGroupCode($attribute);
                $this->recordAttributeRepository->save($attribute);
            }
        }
    }

    /**
     * @return Collection
     */
    public function getRecordCollection()
    {
        /** @var Collection $collection */
        $collection = $this->formRecordFactory->create()->getCollection();
        $collection->setStoreId($this->getStoreId());
        $collection->addFormFilter($this);
        $collection->getResource()->setCurrentForm($this);
        return $collection;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getRecordById($id, $graceful = false)
    {
        $record = $this->formRecordFactory->create();
        $record->setStoreId($this->getStoreId());
        $record->getResource()->setCurrentForm($this);
        $record->getResource()->load($record, $id);
        if (!$graceful && (!$record->getId() || $record->getFormId() != $this->getId())) {
            throw new NoSuchEntityException(__('Form record not found.'));
        }

        return $record;
    }

    /**
     *
     */
    private function updateFields()
    {
        $removedFields = explode(',', (string) $this->getFormRemovedFields());
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
            $this->updateFieldGroupCode($attribute);
            $this->recordAttributeRepository->save($attribute);
        }
    }

    /**
     * @return ResourceModel\FormRecord\Attribute\Collection
     */
    public function getFieldsCollection(): ResourceModel\FormRecord\Attribute\Collection
    {
        if ($this->fieldsCollection === null) {
            $attributeObject = $this->recordAttributeRepository->getAttributeFactory()->create();
            $this->fieldsCollection = $attributeObject->getCollection();
            $this->fieldsCollection->addFieldToFilter('form_id', $this->getId());
            $this->fieldsCollection->setOrder(
                'sort_order',
                \Magento\Framework\Data\Collection::SORT_ORDER_ASC
            );

        }
        return $this->fieldsCollection;
    }

    /**
     * @return array
     */
    public function getFormTabs(): array
    {
        if ($this->formTabs === null) {
            /** @var ResourceModel\FormTab\Collection $formTabsCollection */
            $formTabsCollection = $this->formTabFactory->create()->getCollection();
            $formTabsCollection->addFormFilter($this);
            $this->formTabs = [];
            $lastTab = false;
            foreach ($formTabsCollection as $tab) {
                $this->formTabs[$tab->getId()] = $tab;
                $lastTab = $tab;
            }

            if (!$lastTab) {
                $lastTab = $this->addFormTab(
                    [
                        'label' => __(self::DEFAULT_FORM_TAB_LABEL),
                    ]
                );
            }

            $lastTab->setIsLastTab(true);
        }

        return $this->formTabs;
    }

    /**
     * @param array $tabData
     * @return FormTab
     */
    public function addFormTab(array $tabData = [])
    {
        $this->getFormTabs();
        $tab = $this->formTabFactory->create();
        $tab->setData($tabData);
        $this->formTabs[] = $tab;
        return $tab;
    }

    /**
     * @return mixed
     */
    public function getFirstFormTab()
    {
        $this->getFormTabs();
        return reset($this->formTabs);
    }

    /**
     * @return string[]
     */
    public function getIdentities(): array
    {
        return [
            self::CACHE_TAG . '_' . $this->getId(),
        ];
    }

    /**
     * @return string[]
     */
    public function getCacheTags()
    {
        return $this->getIdentities();
    }

    /**
     * @param Attribute $recordAttribute
     * @return void
     */
    private function updateFieldGroupCode(Attribute $recordAttribute)
    {
        if ($this->getTabIdsMap() === null) {
            $tabIdsMap = [];
            $formTabs = $this->getFormTabs();
            foreach ($formTabs as $tab) {
                if ($tab->getTmpTabId()) {
                    $tabIdsMap[$tab->getTmpTabId()] = $tab->getId();
                };
            }
            $this->setTabIdsMap($tabIdsMap);
        }

        $tabIdsMap = $this->getTabIdsMap();
        if (isset($tabIdsMap[$recordAttribute->getGroupCode()])) {
            $newGroupId = $tabIdsMap[$recordAttribute->getGroupCode()];
            $recordAttribute->setGroupCode($newGroupId);
        }
    }

    /**
     * @return array|null
     */
    public function getFieldsIdentifierMap()
    {
        if ($this->fieldsIdentifierMap === null) {
            $this->fieldsIdentifierMap = [];
            $fields = $this->getFieldsCollection();
            foreach ($fields as $field) {
                if ($field->getIdentifier()) {
                    $this->fieldsIdentifierMap[$field->getIdentifier()] = $field->getAttributeCode();
                }
            }
        }
        return $this->fieldsIdentifierMap;
    }

    /**
     * @param string $attributeCode
     * @return string
     */
    public function getMappedFieldCode(string $attributeCode)
    {
        $fieldsIdentifierMap = $this->getFieldsIdentifierMap();
        return $this->fieldsIdentifierMap[$attributeCode] ?? $attributeCode;
    }

    /**
     * @param string $code
     * @return \Magento\Framework\DataObject|null
     */
    public function getFieldByIdentifier($code)
    {
        $code = $this->getMappedFieldCode($code);
        $fields = $this->getFieldsCollection();
        return $fields->getItemByColumnValue('attribute_code', $code);
    }
}
