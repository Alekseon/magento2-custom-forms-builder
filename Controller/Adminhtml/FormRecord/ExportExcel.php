<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class ExportExcel
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord
 */
class ExportExcel extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord
    implements HttpPostActionInterface, HttpGetActionInterface
{
    /**
     * @return \Magento\Framework\App\ResponseInterface | void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $form = $this->initForm();

        if (!$form->getId()) {
            $this->_forward('noroute');
            return;
        }

        $fileName = 'export_form_' . $form->getId() . '.xml';
        $grid = $this->_view->getLayout()->createBlock(\Alekseon\CustomFormsBuilder\Block\Adminhtml\FormRecord\Grid::class);
        return $this->fileFactory->create($fileName, $grid->getExcelFile($fileName), DirectoryList::VAR_DIR);
    }
}
