<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Controller\Adminhtml\Form;

use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 * Class Edit
 * @package Alekseon\CustomFormsBuilder\Controller\Adminhtml\Form
 */
class Edit extends \Alekseon\CustomFormsBuilder\Controller\Adminhtml\Form implements HttpGetActionInterface
{
    /**
     * @return void
     */
    public function execute()
    {
        $this->_initAction();
        $entity = $this->initForm();

        if ($entity->getId()) {
            $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Edit Form'). ' ' . $entity->getTitle());
        } else {
            $this->_view->getPage()->getConfig()->getTitle()->prepend(__('New Custom Form'));
        }

        $this->_view->renderLayout();
    }
}
