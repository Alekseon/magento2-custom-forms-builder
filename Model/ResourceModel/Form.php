<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Model\ResourceModel;

/**
 * Class Form
 * @package Alekseon\CustomFormsBuilder\Model\ResourceModel
 */
class Form extends \Alekseon\AlekseonEav\Model\ResourceModel\Entity
{
    /**
     * @var string
     */
    protected $entityTypeCode = 'alekseon_custom_form';

    /**
     * @var string
     */
    protected $imagesDirName = 'alekseon_custom_forms';

    /**
     * Form constructor.
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param Form\Attribute\CollectionFactory $attributeCollectionFactory
     * @param null $connectionName
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Alekseon\CustomFormsBuilder\Model\ResourceModel\Form\Attribute\CollectionFactory $attributeCollectionFactory,
        $connectionName = null
    ) {
        $this->attributeCollectionFactory = $attributeCollectionFactory;
        parent::__construct($context, $connectionName);
    }

    /**
     * @return void
     */
    protected function _construct() // @codingStandardsIgnoreLine
    {
        $this->_init('alekseon_custom_form', 'entity_id');
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return Form
     * @throws \Exception
     */
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object) // @codingStandardsIgnoreLine
    {
        $formTabs = $object->getFormTabs();
        if ($formTabs !== null) {
            foreach ($formTabs as $tab) {
                if ($tab->getDeleted()) {
                    $tab->getResource()->delete($tab);
                    continue;
                }
                if (!$tab->getId()) {
                    $tab->setFormId($object->getId());
                }
                $tab->getResource()->save($tab);
            }
        }

        return parent::_afterSave($object);
    }
}
