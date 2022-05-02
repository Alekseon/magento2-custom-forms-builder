<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord;

/**
 * Class Index
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord
 */
class Index extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\FormRecord
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
            return $this->_forward('noroute');
        }

        $this->_initAction();
        $this->_view->getPage()->getConfig()->getTitle()->prepend($form->getTitle());
        $this->_view->renderLayout();
    }
}
