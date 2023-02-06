<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Block\Adminhtml\FormRecord\Edit\Tab;

use Alekseon\CustomFormsBuilder\Model\Form;
use Magento\Backend\Block\Widget\Form\Generic;

/**
 *
 */
class Fields extends \Alekseon\AlekseonEav\Block\Adminhtml\Entity\Edit\Form
{
    protected $formTabCodes = [];

    /**
     * @return Fields
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm() // @codingStandardsIgnoreLine
    {
        $form = $this->getRecordForm();

        if ($form && $this->groupFieldsInTabs()) {
            return Generic::_prepareForm();
        }

        if (!$form) {
            $form = $this->_formFactory->create();
            $this->setForm($form);
        }

        $formTabs = $this->getDataObject()->getForm()->getFormTabs();
        foreach ($formTabs as $formTab) {
            $this->formTabCodes[$formTab['code']] = $formTab['code'];
        }

        if ($this->groupFieldsInTabs()) {
            $fieldset = $form->addFieldset('fieldset', []);
            $this->addFields($fieldset, $this->getTabCode());
        } else {
            $this->setIsFirstTab(true);
            foreach ($formTabs as $formTab) {
                $fieldset = $form->addFieldset('fieldset_' . $formTab['code'], ['legend' => $formTab['label']]);
                $this->addFields($fieldset, $formTab['code']);
                $this->setIsFirstTab(false);
            }
        }

        return parent::_prepareForm();
    }

    /**
     * @return void
     */
    protected function addFields($fieldset, $tabCode)
    {
        $dataObject = $this->getDataObject();
        if ($this->getIsFirstTab()) {
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
            $formTabs = $dataObject->getForm()->getFormTabs();
            $excluded = array_diff($this->formTabCodes, [$tabCode]);
            $this->addAllAttributeFields($fieldset, $dataObject, ['excluded' => $excluded]);
        } else {
            $this->addAllAttributeFields($fieldset, $dataObject, ['included' => [$tabCode]]);
        }
    }

    /**
     * @return mixed
     */
    public function getDataObject()
    {
        return $this->getLayout()->getBlock('form_record_edit')->getCurrentRecord();
    }

    /**
     * @return Fields
     */
    protected function _initFormValues()
    {
        $this->getForm()->addValues($this->getDataObject()->getData());
        return parent::_initFormValues();
    }
    
    /**
     * @return bool
     */
    public function groupFieldsInTabs()
    {
        if ($this->getDataObject()->getForm()->getGroupFieldsIn() == Form::GROUP_FIELDS_IN_TABS_OPTION) {
            return true;
        }
        return false;
    }
}
