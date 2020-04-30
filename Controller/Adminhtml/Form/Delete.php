<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\Form;

/**
 * Class Delete
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\Form
 */
class Delete extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\Form
{
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|mixed
     */
    public function execute()
    {
        $form = $this->initForm();
        if ($form->getId()) {
            try {
                $form->delete();
                $this->messageManager->addSuccess(__('You deleted the form.'));
                return $this->returnResult('*/*/', []);
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $this->returnResult('*/*/', []);
            }
        }
        $this->messageManager->addError(__('We can\'t find an form to delete.'));
        return $this->returnResult('*/*/', []);
    }
}
