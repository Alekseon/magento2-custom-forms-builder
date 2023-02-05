<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Block\Adminhtml\FormRecord\Edit;

use Alekseon\AlekseonEav\Model\Adminhtml\System\Config\Source\InputType;
use Alekseon\AlekseonEav\Api\Data\EntityInterface;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Form
 * @package Alekseon\CustomFormsBuilder\Block\Adminhtml\FormRecord\Edit
 */
class Form extends \Alekseon\AlekseonEav\Block\Adminhtml\Entity\Edit\Form
{
    /**
     * @var
     */
    protected $dataObject;

    /**
     * @return mixed
     */
    public function getDataObject()
    {
        if (null === $this->dataObject) {
            return $this->_coreRegistry->registry('current_record');
        }
        return $this->dataObject;
    }

    /**
     * @return \Magento\Framework\Data\Form|mixed
     */
    protected function getCurrentFormObject()
    {
        return $this->_coreRegistry->registry('current_form');
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $dataObject = $this->getDataObject();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getData('action'),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data',
                ]
            ]
        );

        $formTabs = $dataObject->getForm()->getFormTabs();
        $fieldsets = [];
        foreach ($formTabs as $formTab) {
            $fieldset = $form->addFieldset('fieldset-' . $formTab['code'], ['legend' =>  $formTab['label']]);
            $fieldsets[$formTab['code']] = $fieldset;
        }

        $counter = 0;
        foreach ($fieldsets as $tabCode => $fieldset) {
            if ($counter == 0) {
                if ($dataObject->getId()) {
                    $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
                }
                $excluded = array_keys($fieldsets);
                $excluded = array_diff($excluded, [$tabCode]);
                $this->addAllAttributeFields($fieldset, $dataObject, ['excluded' => $excluded]);
            } else {
                $this->addAllAttributeFields($fieldset, $dataObject, ['included' => [$tabCode]]);
            }

            $counter ++;
        }

        $this->setForm($form);
        $this->getForm()->setUseContainer(true);

        return parent::_prepareForm();
    }


    /**
     * Initialize form fileds values
     *
     * @return $this
     */
    protected function _initFormValues()
    {
        $this->getForm()->addValues($this->getDataObject()->getData());
        return parent::_initFormValues();
    }
}
