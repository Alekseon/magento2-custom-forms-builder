<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Block\Adminhtml\Form;

/**
 * Class Edit
 * @package Alekseon\CustomFormsBuilder\Block\Adminhtml\Form
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Block group name
     *
     * @var string
     */
    protected $_blockGroup = 'Alekseon_CustomFormsBuilder';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'entity_id';
        $this->_controller = 'adminhtml_form';

        parent::_construct();

        if (!$this->_authorization->isAllowed('Alekseon_CustomFormsBuilder::manage_custom_forms')) {
            $this->removeButton('delete');
        }

        $this->addButton(
            'save_and_continue',
            [
                'label' => __('Save and Continue'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
            ]
        );
    }

    /**
     * Retrieve URL for save
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->getUrl(
            '*/*/save',
            ['_current' => true]
        );
    }

    /**
     * @inheritdoc
     */
    public function getBackUrl()
    {
        if ($this->getRequest()->getParam('back_to_records')) {
            return $this->getUrl('*/formRecord/index', ['id' => $this->getRequest()->getParam('entity_id')]);
        } else {
            return parent::getBackUrl();
        }
    }
}
