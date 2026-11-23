<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Model\FormRecord;

use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class AttributeRepository
 * @package Alekseon\CustomFormsBuilder\Model\FormRecord
 */
class AttributeRepository extends \Alekseon\AlekseonEav\Model\AttributeRepository
{
    /**
     * @var AttributeFactory
     */
    protected $attributeFactory;

    /**
     * AttributeRepository constructor.
     * @param AttributeFactory $attributeFactory
     */
    public function __construct(
        \Alekseon\CustomFormsBuilder\Model\FormRecord\AttributeFactory $attributeFactory
    ) {
        $this->attributeFactory = $attributeFactory;
    }

    /**
     * @return AttributeFactory
     */
    public function getAttributeFactory()
    {
        return $this->attributeFactory;
    }

    /**
     * Load form field by id, only if it belongs to given form
     *
     * @param int $attributeId
     * @param int $formId
     * @return Attribute
     * @throws NoSuchEntityException
     */
    public function getByIdForForm(int $attributeId, int $formId)
    {
        $attribute = $this->getById($attributeId);
        if (!$formId || (int) $attribute->getFormId() !== $formId) {
            throw new NoSuchEntityException(
                __('Form field with id "%1" does not exist in form "%2".', $attributeId, $formId)
            );
        }
        return $attribute;
    }
}
