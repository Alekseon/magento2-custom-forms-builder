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
     * @var \Alekseon\CustomFormsBuilder\Model\ResourceModel\Form
     */
    private $formResource;

    /**
     * Initialize dependencies.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Alekseon\CustomFormsBuilder\Model\FormRepository $formRepository
     * @param \Alekseon\CustomFormsBuilder\Model\ResourceModel\Form $formResource
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Alekseon\CustomFormsBuilder\Model\FormRepository $formRepository,
        \Alekseon\CustomFormsBuilder\Model\ResourceModel\Form $formResource
    ) {
        $this->formResource = $formResource;
        parent::__construct($context, $coreRegistry, $formRepository);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $form = $this->initForm();
        if ($form->getId()) {
            try {
                $this->formResource->delete($form);
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
