<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Block\Adminhtml\FormRecord\Edit;

/**
 * Class Tabs
 * @package Alekseon\CustomFormsBuilder\Block\Adminhtml\Form\Edit
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('form_record_tabs');
        $this->setDestElementId('edit_form');
    }

    /**
     * @return Tabs|\Magento\Backend\Block\Widget\Tabs
     * @throws \Exception
     */
    protected function _beforeToHtml()
    {
        if ($this->getCurrentForm()->getGroupFieldsIn() == \Alekseon\CustomFormsBuilder\Model\Form::GROUP_FIELDS_IN_TABS_OPTION) {
            $formTabs = $this->getCurrentForm()->getFormTabs();

            $firstFormTab = $this->getCurrentForm()->getFirstFormTab();
            foreach ($formTabs as $tab) {
                $isFirstTab = $firstFormTab->getId() == $tab->getId();
                $fieldsBlock = $this->getLayout()->createBlock(
                    'Alekseon\CustomFormsBuilder\Block\Adminhtml\FormRecord\Edit\Tab\Fields'
                );
                $fieldsBlock->setTabCode($tab->getId());
                $fieldsBlock->setIsFirstTab($isFirstTab);
                $this->addTab(
                    'form-tab-' . $tab->getId(),
                    [
                        'label' => $tab->getLabel(),
                        'title' => $tab->getLabel(),
                        'active' => $isFirstTab,
                        'content' => $fieldsBlock->toHtml(),
                    ]
                );
            }
        } else {
            $fieldsBlock = $this->getLayout()->createBlock(
                'Alekseon\CustomFormsBuilder\Block\Adminhtml\FormRecord\Edit\Tab\Fields'
            );
            $this->addTab(
                'form-tab',
                [
                    'label' => __('Record Data'),
                    'title' => __('Record Data'),
                    'active' => true,
                    'content' => $fieldsBlock->toHtml(),
                ]
            );
        }

        return parent::_beforeToHtml();
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCurrentRecord()
    {
        return $this->getLayout()->getBlock('form_record_edit')->getCurrentRecord();
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCurrentForm()
    {
        return $this->getLayout()->getBlock('form_record_edit')->getCurrentForm();
    }
}
