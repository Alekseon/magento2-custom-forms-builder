<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormField;

/**
 * Class Save
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormField
 */
class Save extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormField
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
                $this->messageManager->addSuccess(__('You saved the form field.'));
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
            return $this->returnResult('*/form/edit', ['entity_id' => $attribute->getFormId()]);
        }
        return $this->returnResult('*/form/index', []);
    }
}
