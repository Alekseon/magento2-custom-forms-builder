<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Model\Attribute\InputValidator;

use Alekseon\AlekseonEav\Block\Adminhtml\Entity\Edit\Form;
use Alekseon\AlekseonEav\Model\Attribute\InputValidator\AbstractValidator;
use Alekseon\CustomFormsBuilder\Model\Attribute\Source\SelectFormAttributes;
use Alekseon\CustomFormsBuilder\Model\FormRecord\Attribute;
use Magento\Backend\Block\Template;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class MaxLength
 * @package Alekseon\AlekseonEav\Model\Attribute\InputValidator
 */
class PostalCode extends AbstractValidator
{
    /**
     * @var SelectFormAttributes
     */
    private $optionsSource;
    /**
     * @var \Magento\Directory\Model\Country\Postcode\Config
     */
    private $postCodeConfig;
    /**
     * @var string
     */
    protected $code = 'postal_code';

    /**
     * @param SelectFormAttributes $optionsSource
     * @param array $data
     */
    public function __construct(
        SelectFormAttributes $optionsSource,
        \Magento\Directory\Model\Country\Postcode\Config $postCodeConfig,
        array $data = []
    ) {
        $this->optionsSource = $optionsSource;
        $this->postCodeConfig = $postCodeConfig;
        parent::__construct($data);
    }

    /**
     * @return string
     */
    public function getValidationFieldClass()
    {
        $countryFieldId = $this->attribute->getInputParam('post_code_country_field_id');
        if ($countryFieldId) {
            return 'alekseon-validate-postal-code alekseon-validate-postal-code-country-field-' . $countryFieldId;
        }
        return '';
    }

    /**
     * @inheritDoc
     */
    public function validateValue($value)
    {
        $countryFieldId = $this->attribute->getInputParam('post_code_country_field_id');
        if (!$countryFieldId) {
            return true;
        }
        $entity = $this->getEntity();
        $countryId = $entity->getData($countryFieldId);
        if (!$countryId) {
            return true;
        }

        $postCodes = $this->postCodeConfig->getPostCodes();
        $patterns = $postCodes[$countryId] ?? [];

        if (empty($patterns)) {
            return true;
        }

        foreach ($patterns as $patternData) {
            $pattern = $patternData['pattern'] ?? '';
            if (!$pattern) {
                continue;
            }

            if (preg_match('/' . $pattern. '/', $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getInputParams()
    {
        $inputParams = parent::getInputParams();
        $inputParams['post_code_country_field_id'] = [
            'label' => 'Country Field',
            'type' => 'select',
            'options' => $this->optionsSource->getOptionArray(true),
            'note' => 'Used for Postal Code Validation',
        ];
        return $inputParams;
    }
}
