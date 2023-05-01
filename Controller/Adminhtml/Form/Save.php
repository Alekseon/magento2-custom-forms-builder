<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\Form;

/**
 * Class Save
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\Form
 */
class Save extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\Form
{
    /**
     * @return mixed
     */
    public function execute()
    {
        if ($this->getRequest()->getParam('back', false)) {
            $returnToEdit = true;
        } else {
            $returnToEdit = false;
        }
        $form = false;
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $form = $this->initForm();
            $form->addData($data);

            if (isset($data['form_tabs'])) {
                $fomTabs = $form->getFormTabs();
                foreach ($fomTabs as $tab) {
                    if ($tab->getId() && !isset($data['form_tabs'][$tab->getId()])) {
                        $tab->setDeleted(true);
                    }
                }
                foreach ($data['form_tabs'] as $tabId => $tabData) {
                    $tab = $fomTabs[$tabId] ?? false;
                    if ($tab) {
                        $tab->addData($tabData);
                    } else {
                        $form->addFormTab($tabData);
                    }
                }
            }

            try {
                $form->getResource()->save($form);
                $this->messageManager->addSuccess(__('You saved the form.'));
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $returnToEdit = true;
            }
        }
        if ($returnToEdit && $form) {
            return $this->returnResult('*/*/edit', ['_current' => true, 'entity_id' => $form->getId()]);
        } else {
            return $this->returnResult('*/*/');
        }
    }
}
