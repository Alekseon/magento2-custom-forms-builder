<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\Form;

/**
 * Class Index
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\Form
 */
class Index extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\Form
{
    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $this->_initAction();
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Custom Forms'));
        $this->_view->renderLayout();
    }
}
