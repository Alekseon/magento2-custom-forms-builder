<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class ExportExcel
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord
 */
class ExportExcel extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord
{
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $form = $this->initForm();

        if (!$form->getId()) {
            return $this->_forward('noroute');
        }

        $fileName = 'export_form_' . $form->getId() . '.xml';
        $grid = $this->_view->getLayout()->createBlock(\Alekseon\CustomFormsBuilder\Block\Adminhtml\FormRecord\Grid::class);
        return $this->fileFactory->create($fileName, $grid->getExcelFile($fileName), DirectoryList::VAR_DIR);
    }
}
