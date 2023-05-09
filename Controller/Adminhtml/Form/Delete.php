<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\Form;

use Magento\Framework\App\Action\HttpPostActionInterface;

/**
 * Class Delete
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\Form
 */
class Delete extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\Form implements HttpPostActionInterface
{
    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $form = $this->initForm();
        if ($form->getId()) {
            try {
                $form->delete();
                $this->messageManager->addSuccessMessage(__('You deleted the form.'));
                return $this->returnResult('*/*/', []);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $this->returnResult('*/*/', []);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find an form to delete.'));
        return $this->returnResult('*/*/');
    }
}
