<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml;

use Alekseon\AlekseonEav\Api\Data\EntityInterface;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class Form
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml
 */
abstract class Form extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;
    /**
     * @var \Alekseon\CustomFormsBuilder\Model\FormRepository
     */
    protected $formRepository;

    /**
     * Form constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Alekseon\CustomFormsBuilder\Model\FormRepository $formRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Alekseon\CustomFormsBuilder\Model\FormRepository $formRepository
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->formRepository = $formRepository;
        parent::__construct($context);
    }

    /**
     * @param string $requestParam
     * @param int | null $storeId
     * @return \Alekseon\CustomFormsBuilder\Model\Form
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function initForm(string $requestParam = 'entity_id', int $storeId = null)
    {
        $form = $this->coreRegistry->registry('current_form');
        if (!$form) {
            $entityId = $this->getRequest()->getParam($requestParam, false);
            if ($storeId === null) {
                $storeId = $this->getRequest()->getParam('store');
            }
            $form = $this->formRepository->getById($entityId, $storeId, true);
            $this->coreRegistry->register('current_form', $form);
        }
        return $form;
    }

    /**
     * @return $this
     */
    protected function _initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu(
            'Alekseon_CustomFormsBuilder::custom_form'
        )->_addBreadcrumb(
            __('CMS'),
            __('CMS')
        )->_addBreadcrumb(
            __('Manage Custom Forms'),
            __('Manage Custom Forms')
        );
        return $this;
    }

    /**
     * @param string $path
     * @param array $params
     * @return mixed
     */
    protected function returnResult($path = '', array $params = [])
    {
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath($path, $params);
    }
}
