<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormField;

use Magento\Framework\App\Action\HttpPostActionInterface;

/**
 * Class Save
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormField
 */
class Save extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormField implements HttpPostActionInterface
{
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $attribute = $this->initAttribute();
            $attribute->addData($data);
            try {
                $this->attributeRepository->save($attribute);
                $this->messageManager->addSuccessMessage(__('You saved the form field.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
            return $this->returnResult('*/form/edit', ['entity_id' => $attribute->getFormId()]);
        }
        return $this->returnResult('*/form/index', []);
    }
}
