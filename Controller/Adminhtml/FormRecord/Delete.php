<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord;

use Magento\Framework\App\Action\HttpPostActionInterface;

/**
 * Class Delete
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord
 */
class Delete extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord implements HttpPostActionInterface
{
    /**
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        $form = $this->initForm('form_id');
        try {
            $record = $this->initRecord();
            if ($record->getId()) {
                $record->delete();
                $this->messageManager->addSuccessMessage(__('You deleted the record.'));
            }
            return $this->returnResult('*/*', ['id' => $form->getId()]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
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
