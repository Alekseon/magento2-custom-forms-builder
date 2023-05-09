<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;

/**
 * Class Index
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord
 */
class Index extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord
    implements HttpPostActionInterface, HttpGetActionInterface
{
    /**
     * @param string $formRequestParam
     * @return bool
     */
    protected function _isAllowed($formRequestParam  = 'id')
    {
        return parent::_isAllowed($formRequestParam);
    }

    /**
     * Widget Instances Grid
     *
     * @return void
     */
    public function execute()
    {
        $form = $this->initForm();

        if (!$form->getId()) {
            $this->_forward('noroute');
            return;
        }

        $this->_initAction();

        if ($this->getRequest()->getParam('isAjax')) {
            $this->getResponse()->setBody(
                $this->_view->getLayout()->getBlock('grid')->toHtml()
            );
        } else {
            $this->_view->getPage()->getConfig()->getTitle()->prepend($form->getTitle());
            $this->_view->renderLayout();
        }
    }
}
