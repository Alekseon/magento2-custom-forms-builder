<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class FormRecord
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml
 */
abstract class FormRecord extends \Magento\Backend\App\Action
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
     * @var \Alekseon\CUstomFormsBUilder\Model\FormRecordFactory
     */
    protected $formRecordFactory;
    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    /**
     * FormRecord constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Alekseon\CustomFormsBuilder\Model\FormRepository $formRepository
     * @param \Alekseon\CustomFormsBuilder\Model\FormRecordFactory $formRecordFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Alekseon\CustomFormsBuilder\Model\FormRepository $formRepository,
        \Alekseon\CustomFormsBuilder\Model\FormRecordFactory $formRecordFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->formRepository = $formRepository;
        $this->formRecordFactory = $formRecordFactory;
        $this->fileFactory = $fileFactory;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    protected function _isAllowed($formRequestParam  = 'form_id')
    {
        $manageResource = 'Alekseon_CustomFormsBuilder::manage_custom_forms';
        if ($this->_authorization->isAllowed($manageResource)) {
            return true;
        }

        $form = $this->initForm($formRequestParam);
        if ($form) {
            $resource = $this->getIsAllowedResource($form);
        } else {
            $resource = static::ADMIN_RESOURCE;
        }

        return $this->_authorization->isAllowed($resource);
    }

    /**
     * @param $form
     * @return string
     */
    protected function getIsAllowedResource($form)
    {
        return 'Alekseon_CustomFormsBuilder::custom_form_' . $form->getId();
    }

    /**
     * @param string $requestParam
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function initForm($requestParam = 'id')
    {
        $form = $this->coreRegistry->registry('current_form');
        if (!$form) {
            $fromId = $this->getRequest()->getParam($requestParam, false);
            $form = $this->formRepository->getById($fromId, null, true);
            $this->coreRegistry->register('current_form', $form);
        }
        return $form;
    }

    /**
     * @param string $requestParam
     * @param string $formRequestParam
     * @return mixed
     * @throws LocalizedException
     */
    protected function initRecord($requestParam = 'id', $formRequestParam = 'form_id', $storeId = null)
    {
        if ($storeId === null) {
            $storeId = $this->getRequest()->getParam('store');
        }
        $record = $this->coreRegistry->registry('current_record');
        $form = $this->initForm($formRequestParam);
        if (!$record) {
            $record = $this->formRecordFactory->create();
            $record->setStoreId($storeId);
            $record->getResource()->setCurrentForm($form);
            $recordId = $this->getRequest()->getParam($requestParam, false);
            $record->getResource()->load($record, $recordId);

            if (!$recordId) {
                $record->setFormId($form->getId());
            }
            $this->coreRegistry->register('current_record', $record);
        }

        if (!$form->getId() || $record->getFormId() != $form->getId()) {
            throw new LocalizedException(__('Incorrect Form'));
        }

        return $record;
    }

    /**
     * @return $this
     */
    protected function _initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu(
            'Alekseon_CustomFormsBuilder::custom_form'
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
