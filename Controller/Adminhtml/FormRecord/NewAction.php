<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord;

use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 * Class NewAction
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord
 */
class NewAction extends \Magento\Backend\App\Action implements HttpGetActionInterface
{
    /**
     * Full access, otherwise per-form save resource is checked in _isAllowed()
     */
    const ADMIN_RESOURCE = 'Alekseon_CustomFormsBuilder::manage_custom_forms';

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * NewAction constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
    ) {
        parent::__construct($context);
        $this->resultForwardFactory = $resultForwardFactory;
    }

    /**
     * Adding record requires full access or "custom_form_<id>_save" resource of the form
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        if ($this->_authorization->isAllowed(static::ADMIN_RESOURCE)) {
            return true;
        }

        $formId = (int) $this->getRequest()->getParam('form_id');
        if ($formId <= 0) {
            return false;
        }

        return $this->_authorization->isAllowed(
            'Alekseon_CustomFormsBuilder::custom_form_' . $formId . '_save'
        );
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Forward
     */
    public function execute()
    {
        return $this->resultForwardFactory->create()->forward('edit');
    }
}
