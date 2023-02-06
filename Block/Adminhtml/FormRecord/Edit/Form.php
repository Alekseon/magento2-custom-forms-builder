<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Block\Adminhtml\FormRecord\Edit;

use Alekseon\AlekseonEav\Model\Adminhtml\System\Config\Source\InputType;
use Alekseon\AlekseonEav\Api\Data\EntityInterface;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Form
 * @package Alekseon\CustomFormsBuilder\Block\Adminhtml\FormRecord\Edit
 */
class Form extends \Alekseon\CustomFormsBuilder\Block\Adminhtml\FormRecord\Edit\Tab\Fields
{
    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getData('action'),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data',
                ]
            ]
        );
    
        $this->setRecordForm($form);
        $this->setForm($form);
        $this->getForm()->setUseContainer(true);

        return parent::_prepareForm();
    }
}
