<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\Form;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;

/**
 * Class Index
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\Form
 */
class Index extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\Form
    implements HttpPostActionInterface, HttpGetActionInterface
{
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $this->_initAction();
        if ($this->getRequest()->getParam('isAjax')) {
            $this->getResponse()->setBody(
                $this->_view->getLayout()->getBlock('grid')->toHtml()
            );
            return;
        } else {
            $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Custom Forms'));
            $this->_view->renderLayout();
        }
    }
}
