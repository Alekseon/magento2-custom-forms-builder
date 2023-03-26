<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Block\Adminhtml\FormRecord;

use Alekseon\AlekseonEav\Api\Data\AttributeInterface;
use Alekseon\AlekseonEav\Block\Adminhtml\Entity\Grid as EavGrid;
use Alekseon\CustomFormsBuilder\Model\Form;

/**
 * Class Grid
 * @package Alekseon\CustomFormsBuilder\Block\Adminhtml\FormRecord
 */
class Grid extends EavGrid
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * Grid constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context, $backendHelper, $data);
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('desc');
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
        $collection = $this->getCurrentForm()->getRecordCollection();
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
            'created_at',
            [
                'header' => __('Created At'),
                'index' => 'created_at',
                'gmtoffset' => true,
                'type' => 'datetime',
                'header_css_class' => 'col-updated col-date',
                'column_css_class' => 'col-updated col-date'
            ]
        );

        $this->addColumn(
            'created_at',
            [
                'header' => __('Created At'),
                'index' => 'created_at',
                'gmtoffset' => true,
                'type' => 'datetime',
                'header_css_class' => 'col-updated col-date',
                'column_css_class' => 'col-updated col-date'
            ]
        );

        $this->addColumn(
            'created_from_store_id',
            [
                'header' => __('Created From'),
                'index' => 'created_from_store_id',
                'type' => 'store',
                'store_view' => true
            ]
        );

        $this->addAttributeColumns();

        $this->addExportType('*/*/exportCsv', __('CSV'));
        $this->addExportType('*/*/exportExcel', __('Excel XML'));

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getAttributes()
    {
        return $this->getCurrentForm()->getFieldsCollection();
    }

    /**
     * @return Grid|void
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('records');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl(
                    '*/*/massDelete',
                    [
                        'id' => $this->getCurrentForm()->getId(),
                    ]
                ),
                'confirm' => __('Are you sure?')
            ]
        );

    }

    /**
     * @return Form
     */
    protected function getCurrentForm()
    {
        return $this->coreRegistry->registry('current_form');
    }

    /**
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit',
            [
                'id' => $row->getEntityId(),
                'form_id' => $this->getCurrentForm()->getId()
            ]
        );
    }
}
