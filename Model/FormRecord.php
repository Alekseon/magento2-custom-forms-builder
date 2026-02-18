<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Model;

/**
 * Class FormRecord
 * @package Alekseon\CustomFormsBuilder\Model
 */
class FormRecord extends \Alekseon\AlekseonEav\Model\Entity
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'alekseon_custom_form_builder_form_record';
    /**
     * @var FormRepository
     */
    protected $formRepository;

    /**
     * FormRecord constructor.
     * @param \Alekseon\AlekseonEav\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ResourceModel\FormRecord $resource
     * @param ResourceModel\FormRecord\Collection $resourceCollection
     */
    public function __construct(
        \Alekseon\AlekseonEav\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord $resource,
        \Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord\Collection $resourceCollection,
        \Alekseon\CustomFormsBuilder\Model\FormRepository $formRepository
    ) {
        $this->formRepository = $formRepository;
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection
        );
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getForm()
    {
        return $this->formRepository->getById($this->getFormId());
    }

    /**
     * @param string $attributeCode
     * @return string
     */
    protected function getMappedAttributeCode($attributeCode)
    {
        $notMappedKeys = ['form_id'];
        if (in_array($attributeCode, $notMappedKeys)) {
            return $attributeCode;
        }
        if (!$this->getFormId()) {
            return $attributeCode;
        }
        return $this->getForm()->getMappedFieldCode($attributeCode);
    }

    /**
     * @param string $attributeCode
     * @return \Alekseon\AlekseonEav\Model\Attribute
     */
    public function getAttribute($attributeCode)
    {
        $attributeCode = $this->getMappedAttributeCode($attributeCode);
        return parent::getAttribute($attributeCode);
    }

    /**
     * @param $key
     * @return mixed|void|null
     */
    protected function _getData($key)
    {
        $key = $this->getMappedAttributeCode($key);
        return parent::_getData($key);
    }

    /**
     * @inheritDoc
     */
    public function getData($key = '', $index = null)
    {
        if ('' !== $key && is_string($key)) {
            $key = $this->getMappedAttributeCode($key);
        }
        return parent::getData($key);
    }

    /**
     * @inheritDoc
     */
    public function setData($key, $value = null)
    {
        if (is_string($key)) {
            $key = $this->getMappedAttributeCode($key);
        }
        return parent::setData($key, $value);
    }

    /**
     * @inheritDoc
     */
    public function unsetData($key = null)
    {
        if (is_string($key)) {
            $key = $this->getMappedAttributeCode($key);
        }
        $key = $this->getMappedAttributeCode($key);
        return parent::unsetData($key);
    }

    /**
     * @param $storeId
     * @return FormRecord
     */
    public function setStoreId($storeId)
    {
        if (!$this->getId()) {
            $this->setCreatedFromStoreId($storeId);
        }
        return parent::setStoreId($storeId);
    }
}
