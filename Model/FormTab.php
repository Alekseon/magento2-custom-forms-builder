<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Model;

use Magento\Framework\View\Element\AbstractBlock;

/**
 * @method FormTab setIsFirstTab(bool $isLastTab))
 * @method bool getIsFirstTab()
 * @method FormTab setIsLastTab(bool $isFirstTab)
 * @method bool getIsLastTab()
 */
class FormTab extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @param \Alekseon\AlekseonEav\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ResourceModel\FormTab $resource
     * @param ResourceModel\FormTab\Collection $resourceCollection
     */
    public function __construct(
        \Alekseon\AlekseonEav\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Alekseon\CustomFormsBuilder\Model\ResourceModel\FormTab $resource,
        \Alekseon\CustomFormsBuilder\Model\ResourceModel\FormTab\Collection $resourceCollection
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection
        );
    }
}
