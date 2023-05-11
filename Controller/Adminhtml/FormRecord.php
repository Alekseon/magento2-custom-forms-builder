<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml;

use Magento\Framework\App\Request\DataPersistorInterface;
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
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * FormRecord constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Alekseon\CustomFormsBuilder\Model\FormRepository $formRepository
     * @param \Alekseon\CustomFormsBuilder\Model\FormRecordFactory $formRecordFactory
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Alekseon\CustomFormsBuilder\Model\FormRepository $formRepository,
        \Alekseon\CustomFormsBuilder\Model\FormRecordFactory $formRecordFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        DataPersistorInterface $dataPersistor
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->formRepository = $formRepository;
        $this->formRecordFactory = $formRecordFactory;
        $this->fileFactory = $fileFactory;
        $this->dataPersistor = $dataPersistor;
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
     * @return \Alekseon\CustomFormsBuilder\Model\Form
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function initForm($requestParam = 'id')
    {
        $form = $this->coreRegistry->registry('current_form');
        if (!$form) {
            $fromId = $this->getRequest()->getParam($requestParam, false);
            $storeId = $this->getRequest()->getParam('store', null);
            $form = $this->formRepository->getById($fromId, $storeId, true);

            if (!$this->isSaveRecordAllowed($form)) {
                $form->setSaveRecordDisallowedFlag(true);
            }
            if (!$this->isDeleteRecordAllowed($form)) {
                $form->setDeleteRecordDisallowedFlag(true);
            }
            if (!$this->isManageFormAllowed($form)) {
                $form->setManageFormAllowedDisallowedFlag(true);
            }

            $this->coreRegistry->register('current_form', $form);
        }
        return $form;
    }

    /**
     * @param string $requestParam
     * @param string $formRequestParam
     * @return \Alekseon\CustomFormsBuilder\Model\FormRecord
     * @throws LocalizedException
     */
    protected function initRecord(string $requestParam = 'id', string $formRequestParam = 'form_id')
    {
        $record = $this->coreRegistry->registry('current_record');
        $form = $this->initForm($formRequestParam);
        if (!$record) {
            $recordId = $this->getRequest()->getParam($requestParam, false);
            $record = $form->getRecordById($recordId, true);
            if (!$record->getId()) {
                $record->setFormId($form->getId());
            }

            $data = $this->dataPersistor->get('custom_form_record');
            if (!empty($data)) {
                $record->addData($data);
                $this->dataPersistor->clear('custom_form_record');
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

    /**
     * @param \Alekseon\CustomFormsBuilder\Model\Form $form
     * @return bool
     */
    private function isSaveRecordAllowed(\Alekseon\CustomFormsBuilder\Model\Form $form)
    {
        $manageResource = 'Alekseon_CustomFormsBuilder::manage_custom_forms';
        if ($this->_authorization->isAllowed($manageResource)) {
            return true;
        }

        $resource = 'Alekseon_CustomFormsBuilder::custom_form_' . $form->getId() . '_save';
        return $this->_authorization->isAllowed($resource);
    }

    /**
     * @param \Alekseon\CustomFormsBuilder\Model\Form $form
     * @return bool
     */
    private function isManageFormAllowed(\Alekseon\CustomFormsBuilder\Model\Form $form)
    {
        $manageResource = 'Alekseon_CustomFormsBuilder::manage_custom_forms';
        if ($this->_authorization->isAllowed($manageResource)) {
            return true;
        }

        $resource = 'Alekseon_CustomFormsBuilder::custom_form_' . $form->getId() . '_manage_form';
        return $this->_authorization->isAllowed($resource);
    }

    /**
     * @param \Alekseon\CustomFormsBuilder\Model\Form $form
     * @return bool
     */
    private function isDeleteRecordAllowed(\Alekseon\CustomFormsBuilder\Model\Form $form)
    {
        return $this->isSaveRecordAllowed($form);
    }
}
