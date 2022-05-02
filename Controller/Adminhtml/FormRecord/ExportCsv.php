<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord;

use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class ExportCsv
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord
 */
class ExportCsv extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord
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

        $fileName = 'export_form_' . $form->getId() . '.csv';
        $grid = $this->_view->getLayout()->createBlock(\Alekseon\CustomFormsBuilder\Block\Adminhtml\FormRecord\Grid::class);
        return $this->fileFactory->create($fileName, $grid->getCsvFile($fileName), DirectoryList::VAR_DIR);
    }
}
