<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Block\Adminhtml\Form\Edit;

/**
 * Class Form
 * @package Alekseon\CustomFormsBuilder\Block\Adminhtml\Form\Edit
 */
class Form extends \Alekseon\AlekseonEav\Block\Adminhtml\Entity\Edit\Form
{
    /**
     * @inheritdoc
     */
    public function getDataObject()
    {
        return false;
    }

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

        $this->setForm($form);
        $this->getForm()->setUseContainer(true);

        return parent::_prepareForm();
    }
}
