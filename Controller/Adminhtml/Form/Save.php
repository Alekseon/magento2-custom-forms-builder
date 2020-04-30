<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
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
            try {
                $form->getResource()->save($form);
                $this->messageManager->addSuccess(__('You saved the form.'));
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $returnToEdit = true;
            }
        }
        if ($returnToEdit && $form) {
            return $this->returnResult('*/*/edit', ['entity_id' => $form->getId()]);
        } else {
            return $this->returnResult('*/*/');
        }
    }
}
