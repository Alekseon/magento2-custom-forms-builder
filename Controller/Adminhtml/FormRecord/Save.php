<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord;

/**
 * Class Save
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord
 */
class Save extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord
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

        $form = $this->initForm('form_id');
        $data = $this->getRequest()->getPostValue();
        $recordId = false;

        if ($data) {
            try {
                $record = $this->initRecord();
                $recordId = $record->getId();
                $record->addData($data);
                $record->setFormId($form->getId());
                $record->getResource()->save($record);
                $recordId = $record->getId();
                $this->messageManager->addSuccess(__('You saved the record.'));
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $returnToEdit = true;
            }
        }

        if ($returnToEdit) {
            if ($recordId) {
                return $this->returnResult('*/*/edit', ['form_id' => $form->getId(), 'id' => $recordId]);
            } else {
                return $this->returnResult('*/*/new', ['form_id' => $form->getId()]);
            }
        } else {
            return $this->returnResult('*/*', ['id' => $form->getId()]);
        }
    }
}
