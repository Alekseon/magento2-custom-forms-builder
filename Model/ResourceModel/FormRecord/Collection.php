<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord;

use Alekseon\CustomFormsBuilder\Model\Form;

/**
 * Class Collection
 * @package Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord
 */
class Collection extends \Alekseon\AlekseonEav\Model\ResourceModel\Entity\Collection
{
    /**
     * @var array
     */
    protected $fieldIdentifierMap = [];
    /**
     * @return void
     */
    protected function _construct() // @codingStandardsIgnoreLine
    {
        $this->_init(
            'Alekseon\CustomFormsBuilder\Model\FormRecord',
            'Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord'
        );
    }

    /**
     * @param Form $form
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addFormFilter(Form $form)
    {
        $this->addFieldToFilter('form_id', $form->getId());
        $this->setFieldIdentifierMap($form);
        return $this;
    }

    public function setFieldIdentifierMap(Form $form)
    {
        $fields = $form->getFieldsCollection();
        foreach ($fields as $field) {
            if ($field->getIdentifier()) {
                $this->fieldIdentifierMap[$field->getIdentifier()] = $field->getAttributeCode();
            }
        }
        return $this;
    }

    /**
     * @param string $attributeCode
     * @return mixed
     */
    protected function getMappedAttributeCode($attributeCode)
    {
        if (isset($this->fieldIdentifierMap[$attributeCode])) {
            $attributeCode = $this->fieldIdentifierMap[$attributeCode];
        }
        return $attributeCode;
    }

    /**
     * @param $attribute
     * @param $condition
     * @param $joinType
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getAttributeConditionSql($attribute, $condition, $joinType = 'inner')
    {
        $attribute = $this->getMappedAttributeCode($attribute);
        return parent::getAttributeConditionSql($attribute, $condition, $joinType);
    }

    /**
     * @param $attribute
     * @param $direction
     * @return Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addAttributeToSort($attribute, $direction = self::SORT_ORDER_ASC)
    {
        $attribute = $this->getMappedAttributeCode($attribute);
        return parent::addAttributeToSort($attribute, $direction);
    }

    /**
     * @param $attribute
     * @return Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addAttributeToSelect($attribute)
    {
        if (!is_array($attribute)) {
            $attribute = $this->getMappedAttributeCode($attribute);
        }
        return parent::addAttributeToSelect($attribute);
    }
}
