<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord;

/**
 * Class Attribute
 * @package Alekseon\CustomFormsBuilder\Model\ResourceModel\FormRecord
 */
class Attribute extends \Alekseon\AlekseonEav\Model\ResourceModel\Attribute
{
    /**
     * @var string
     */
    protected $entityTypeCode = 'alekseon_custom_form_record';
    /**
     * @var string
     */
    protected $mainTable = 'alekseon_custom_form_record_attribute';
    /**
     * @var string
     */
    protected $backendTablePrefix = 'alekseon_custom_form_record_entity';
    /**
     * @var string
     */
    protected $attributeOptionTable = 'alekseon_custom_form_record_attribute_option';
    /**
     * @var string
     */
    protected $attributeOptionValueTable = 'alekseon_custom_form_record_attribute_option_value';
    /**
     * @var string
     */
    protected $attributeFrontendLabelsTable =  'alekseon_custom_form_record_attribute_frontend_label';
}
