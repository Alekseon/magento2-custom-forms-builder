<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord;

use Magento\Framework\App\Action\HttpPostActionInterface;

/**
 * Class Save
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord
 */
class Save extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord implements HttpPostActionInterface
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
        $record = $this->initRecord();

        if ($data) {
            try {
                $record->addData($data);
                $record->setFormId($form->getId());
                $record->getResource()->save($record);
                $this->dataPersistor->clear('custom_form_record');
                $this->messageManager->addSuccessMessage(__('You saved the record.'));
            } catch (\Exception $e) {
                $this->dataPersistor->set('custom_form_record', $data);
                $this->messageManager->addErrorMessage($e->getMessage());
                $returnToEdit = true;
            }
        }

        if ($returnToEdit) {
            if ($record->getId()) {
                return $this->returnResult('*/*/edit', ['_current' => true, 'id' => $record->getId()]);
            } else {
                return $this->returnResult('*/*/new', ['_current' => true]);
            }
        } else {
            return $this->returnResult('*/*', ['id' => $form->getId()]);
        }
    }

    /**
     * @param $form
     * @return string
     */
    protected function getIsAllowedResource($form)
    {
        return 'Alekseon_CustomFormsBuilder::custom_form_' . $form->getId() . '_save';
    }
}
