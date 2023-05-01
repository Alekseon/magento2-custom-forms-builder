<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Block\Adminhtml\Form;

use Alekseon\AlekseonEav\Block\Adminhtml\Entity\Grid as EavGrid;

/**
 * Class Grid
 * @package Alekseon\CustomFormsBuilder\Block\Adminhtml\Form
 */
class Grid extends EavGrid
{
    /**
     * @var \Alekseon\CustomFormsBuilder\Model\ResourceModel\Form\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * Grid constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Alekseon\CustomFormsBuilder\Model\ResourceModel\Form\CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Alekseon\CustomFormsBuilder\Model\ResourceModel\Form\CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setUseAjax(true);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->_collectionFactory->create();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function _prepareColumns()
    {
        parent::_prepareColumns();

        $this->addColumn(
            'entity_id',
            [
                'header' => __('Form Id'),
                'index' => 'entity_id',
            ]
        );

        $this->addAttributeColumns();

        $this->addColumn(
            'admin_note',
            [
                'header' => __('Admin Note'),
                'index' => 'admin_note',
            ]
        );

        $this->addColumn(
            'actions',
            [
                'header' => __('Actions'),
                'sortable' => false,
                'filter' => false,
                'renderer' => \Alekseon\CustomFormsBuilder\Block\Adminhtml\Form\Grid\Renderer\Actions::class,
                'header_css_class' => 'col-actions',
                'column_css_class' => 'col-actions'
            ]
        );

        return $this;
    }

    /**
     * @param \Magento\Framework\DataObject $row
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getRowUrl($row)
    {
        return false;
    }
}
