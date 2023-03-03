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
        $form = $this->_formFactory->create();
        $this->setForm($form);

        $formTabs = $this->getDataObject()->getForm()->getFormTabs();
        $currentTabLabel = '';
        foreach ($formTabs as $formTab) {
            $this->formTabCodes[$formTab->getId()] = $formTab->getId();
            if ($formTab->getId() == $this->getTabCode()) {
                $currentTabLabel = $formTab->getLabel();
            }
        }

        if ($this->groupFieldsInTabs()) {
            $fieldset = $form->addFieldset('fieldset', ['legend' => $currentTabLabel]);
            $this->addFields($fieldset, $this->getTabCode());
        } else {
            $this->setIsFirstTab(true);
            foreach ($formTabs as $formTab) {
                $fieldset = $form->addFieldset('fieldset_' . $formTab->getId(), ['legend' => $formTab->getLabel()]);
                $this->addFields($fieldset, $formTab->getId());
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
            if ($dataObject->getId()) {
                $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
            }
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
