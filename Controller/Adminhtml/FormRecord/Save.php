<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord;

use Magento\Framework\App\Action\HttpPostActionInterface;

/**
 * Class Save
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord
 */
class Save extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord implements HttpPostActionInterface
{
    /**
     * @var \Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord
     */
    private $recordResource;

    /**
     * Initialize dependencies.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Alekseon\CustomFormsBuilder\Model\FormRepository $formRepository
     * @param \Alekseon\CustomFormsBuilder\Model\FormRecordFactory $formRecordFactory
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord $recordResource
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Alekseon\CustomFormsBuilder\Model\FormRepository $formRepository,
        \Alekseon\CustomFormsBuilder\Model\FormRecordFactory $formRecordFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord $recordResource
    ) {
        $this->recordResource = $recordResource;
        parent::__construct($context, $coreRegistry, $formRepository, $formRecordFactory, $fileFactory, $dataPersistor);
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        if ($this->getRequest()->getParam('back', false)) {
            $returnToEdit = true;
        } else {
            $returnToEdit = false;
        }

        $form = $this->initForm('form_id');
        $data = $this->getRequest()->getPostValue();
        $record = $this->initRecord();

        if ($data) {
            try {
                $record->addData($data);
                $record->setFormId($form->getId());
                $this->recordResource->save($record);
                $this->dataPersistor->clear('custom_form_record');
                $this->messageManager->addSuccessMessage(__('You saved the record.'));
            } catch (\Exception $e) {
                $this->dataPersistor->set('custom_form_record', $data);
                $this->messageManager->addErrorMessage($e->getMessage());
                $returnToEdit = true;
            }
        }

        if ($returnToEdit) {
            if ($record->getId()) {
                return $this->returnResult('*/*/edit', ['_current' => true, 'id' => $record->getId()]);
            } else {
                return $this->returnResult('*/*/new', ['_current' => true]);
            }
        } else {
            return $this->returnResult('*/*', ['id' => $form->getId()]);
        }
    }

    /**
     * @param $form
     * @return string
     */
    protected function getIsAllowedResource($form)
    {
        return 'Alekseon_CustomFormsBuilder::custom_form_' . $form->getId() . '_save';
    }
}
