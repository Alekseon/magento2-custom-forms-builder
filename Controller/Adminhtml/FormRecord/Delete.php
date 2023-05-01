<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord;

/**
 * Class Delete
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord
 */
class Delete extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord
{
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|mixed
     */
    public function execute()
    {
        $form = $this->initForm('form_id');
        try {
            $record = $this->initRecord();
            if ($record->getId()) {
                $record->delete();
                $this->messageManager->addSuccess(__('You deleted the record.'));
            }
            return $this->returnResult('*/*', ['id' => $form->getId()]);
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
            return $this->returnResult('*/*', ['id' => $form->getId()]);
        }
        $this->messageManager->addError(__('We can\'t find an record to delete.'));
        return $this->returnResult('*/*', ['id' => $form->getId()]);
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
