<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord;

/**
 * Class Edit
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord
 */
class Edit extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $form = $this->initForm('form_id');

        try {
            $record = $this->initRecord();
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
            return $this->returnResult('*/*', ['id' => $form->getId()]);
        }

        $this->_initAction();
        if ($record->getId()) {
            $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Edit Form Record'));
        } else {
            $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Create New Form Record'));
        }

        $this->_view->renderLayout();
    }
}
