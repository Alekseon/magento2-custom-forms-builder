<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord;

use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 * Class Edit
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord
 */
class Edit extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord implements HttpGetActionInterface
{
    /**
     * @return void
     */
    public function execute()
    {
        $form = $this->initForm('form_id');

        try {
            $record = $this->initRecord();
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->returnResult('*/*', ['id' => $form->getId()]);
        }

        $this->_initAction();
        $title = $record->getForm()->getTitle();
        $this->_view->getPage()->getConfig()->getTitle()->prepend($title);
        $this->_view->renderLayout();
    }
}
