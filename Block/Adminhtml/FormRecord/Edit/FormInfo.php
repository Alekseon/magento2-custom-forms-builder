<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Block\Adminhtml\FormRecord\Edit;

/**
 *
 */
class FormInfo extends \Magento\Backend\Block\Template
{
    protected $_template = 'Alekseon_CustomFormsBuilder::formRecord/edit/formInfo.phtml';

    /**
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _construct()
    {
        if ($this->getCurrentRecord()->getId()) {
            $this->setTitle(__('Record #') . $this->getCurrentRecord()->getId());
        } else {
            $this->setTitle(__('New Record'));
        }
        parent::_construct(); // TODO: Change the autogenerated stub
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCurrentRecord()
    {
        return $this->getLayout()->getBlock('form_record_edit')->getCurrentRecord();
    }

    /**
     * @return string|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCreatedFrom()
    {
        if ($this->getCurrentRecord()->getCreatedFromStoreId()) {
            try {
                $store = $this->_storeManager->getStore($this->getCurrentRecord()->getCreatedFromStoreId());
                return $store->getName();
            } catch (\Exception $e) {
                // do nothing
            }
        }
    }
}
