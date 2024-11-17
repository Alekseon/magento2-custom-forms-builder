<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Model\Attribute\Source;

use Alekseon\CustomFormsBuilder\Model\Form;

/**
 * Class TextFormAttributes
 * @package Alekseon\CustomFormsBuilder\Model\Attribute\Source
 */
class SelectFormAttributes extends \Alekseon\AlekseonEav\Model\Attribute\Source\AbstractSource
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * TextFormAttributes constructor.
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        \Magento\Framework\Registry $coreRegistry
    )
    {
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * @inheritDoc
     */
    protected function getEmptyOptionLabel()
    {
        return __('Not Selected');
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $options = [];

        /** @var Form $form */
        $form = $this->coreRegistry->registry('current_form');

        if ($form) {
            $fields = $form->getFieldsCollection();
            foreach ($fields as $field) {
                if ($field->getFrontendInput() == 'select') {
                    $options[$field->getAttributeCode()] = $field->getFrontendLabel();
                }
            }
        }

        return $options;
    }
}
