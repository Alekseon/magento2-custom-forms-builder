<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormField;

use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 * Class Edit
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormField
 */
class Edit extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormField implements HttpGetActionInterface
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|mixed
     */
    public function execute()
    {
        try {
            $attribute = $this->initAttribute();
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->returnResult('*/form/index');
        }
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        if ($attribute) {
            $resultPage->getConfig()->getTitle()->prepend(__('Edit Form Field') . ' ' . $attribute->getFrontendLabel());
        } else {
            $this->messageManager->addErrorMessage(__('Form Field not found.'));
            return $this->returnResult('*/form/index');
        }
        return $resultPage;
    }
}
