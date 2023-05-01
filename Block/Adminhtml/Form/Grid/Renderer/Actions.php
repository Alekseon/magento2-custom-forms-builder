<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Block\Adminhtml\Form\Grid\Renderer;

/**
 * Class Actions
 * @package Alekseon\CustomFormsBuilder\Block\Adminhtml\Form\Grid\Renderer
 */
class Actions extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Action
{
    /**
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $actions = [
            '<a href = "' . $this->getUrl('*/formRecord', ['id' => $row->getId()]) . '">' . __('Show Records') . '</a>',
            '<a href = "' . $this->getUrl('*/*/edit', ['entity_id' => $row->getId()]) . '">' . __('Manage Form') . '</a>'
        ];
        return implode(' | ', $actions);
    }
}
