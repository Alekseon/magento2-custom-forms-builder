<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord;

use Alekseon\CustomFormsBuilder\Model\Form;
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
     * @return \Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord\Collection
     * @throws LocalizedException
     */
    protected function getCollection()
    {
        /** @var Form $form */
        $form = $this->coreRegistry->registry('current_form');
        $collection = $form->getRecordCollection();
        $collection->addFieldToFilter(
            'entity_id',
            $this->getRequest()->getParam('records')
        );

        return $collection;
    }
}
