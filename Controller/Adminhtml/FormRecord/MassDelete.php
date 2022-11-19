<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class MassDelete
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord
 */
class MassDelete extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord
{
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $form = $this->initForm();

        if (!$form->getId()) {
            return $this->_forward('noroute');
        }

        $recordIds = $this->getRequest()->getParam('records');

        if (!is_array($recordIds)) {
            $this->messageManager->addErrorMessage(__('Please select form record(s).'));
        } else {
            try {
                $counter = 0;
                foreach ($this->getCollection() as $model) {
                    $model->delete();
                    $counter ++;
                }
                $this->messageManager->addSuccessMessage(
                    __('A total of %1 record(s) have been deleted.', $counter)
                );
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while deleting these records.'));
            }
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('*/formRecord', ['id' => $form->getId()]);
        return $resultRedirect;
    }

    /**
     * @return \Magento\Framework\Data\Collection\AbstractDb|\Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection|null
     */
    protected function getCollection()
    {
        $form = $this->coreRegistry->registry('current_form');
        $collection = $this->formRecordFactory->create()->getCollection();
        $collection->addFieldToFilter(
            'entity_id',
            $this->getRequest()->getParam('records')
        )->addFieldToFilter(
            'form_id',
            $form->getId()
        );

        return $collection;
    }
}
