<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Model\ResourceModel\Form;

/**
 * Class Attribute
 * @package Alekseon\CustomFormsBuilder\Model\ResourceModel\Form
 */
class Attribute extends \Alekseon\AlekseonEav\Model\ResourceModel\Attribute
{
    /**
     * @var string
     */
    protected $entityTypeCode = 'alekseon_custom_form';
    /**
     * @var string
     */
    protected $mainTable = 'alekseon_custom_form_attribute';
    /**
     * @var string
     */
    protected $backendTablePrefix = 'alekseon_custom_form_entity';
    /**
     * @var string
     */
    protected $attributeOptionTable = 'alekseon_custom_form_attribute_option';
    /**
     * @var string
     */
    protected $attributeOptionValueTable = 'alekseon_custom_form_attribute_option_value';
    /**
     * @var string
     */
    protected $attributeFrontendLabelsTable =  'alekseon_custom_form_attribute_frontend_label';
}
