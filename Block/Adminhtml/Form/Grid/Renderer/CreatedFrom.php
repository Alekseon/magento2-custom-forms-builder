<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Block\Adminhtml\Form\Grid\Renderer;

/**
 *
 */
class CreatedFrom extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Store
{
    /**
     * @param \Magento\Framework\DataObject $row
     * @return \Magento\Framework\Phrase|string|null
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        if ($row->getCreatedFromStoreId() === '0') {
            return __('All Store Views');
        }
        return parent::render($row);
    }
}
