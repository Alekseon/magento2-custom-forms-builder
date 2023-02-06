<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Block\Adminhtml\Form\Edit\Tab;

use Alekseon\AlekseonEav\Model\Adminhtml\System\Config\Source\InputType;
use Alekseon\AlekseonEav\Api\Data\EntityInterface;
use Alekseon\CustomFormsBuilder\Model\Form;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class General
 * @package Alekseon\CustomFormsBuilder\Block\Adminhtml\Form\Edit\Tab
 */
class General extends \Alekseon\AlekseonEav\Block\Adminhtml\Entity\Edit\Form implements
    \Magento\Backend\Block\Widget\Tab\TabInterface
{
    protected $dataObject;

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('General');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('General');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function getDataObject()
    {
        if (null === $this->dataObject) {
            return $this->_coreRegistry->registry('current_form');
        }
        return $this->dataObject;
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $dataObject = $this->getDataObject();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $baseFieldset = $form->addFieldset('base_fieldset', ['legend' => __('Form Properties')]);

        if ($dataObject->getId()) {
            $baseFieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        }

        $this->addAllAttributeFields($baseFieldset, $dataObject);

        $baseFieldset->addField('identifier',
            'text',
            [
                'name' => 'identifier',
                'label' => __('Form Identifier'),
                'title' => __('Form Identifier'),
                'note' => __(
                    'This is used internally. Make sure you don\'t use spaces or more than %1 symbols.',
                    255
                ),
            ]
        );

        $baseFieldset->addField('group_fields_in',
            'select',
            [
                'name' => 'group_fields_in',
                'label' => __('Group Fields In'),
                'title' => __('Group Fields In'),
                'options' => [
                    Form::GROUP_FEILDS_IN_FIELDSETS_OPTION => __('Fieldsets'),
                    Form::GROUP_FIELDS_IN_TABS_OPTION => __('Tabs'),
                ],
            ]
        );


        $adminNoteFieldset = $form->addFieldset('admin_note_fieldset', ['legend' => __('Admin Note')]);

        $adminNoteFieldset->addField('admin_note',
            'textarea',
            [
                'name' => 'admin_note',
                'label' => 'Admin Note',
                'title' => 'Admin Note',
                'note' => __(
                    'Note is not visible on forntend.'
                ),
            ]
        );


        $this->setForm($form);

        return parent::_prepareForm();
    }


    /**
     * Initialize form fileds values
     *
     * @return $this
     */
    protected function _initFormValues()
    {
        $this->getForm()->addValues($this->getDataObject()->getData());
        return parent::_initFormValues();
    }
}
