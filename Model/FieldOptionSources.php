<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
declare(strict_types=1);

namespace Alekseon\CustomFormsBuilder\Model;

/**
 * Class FieldOptionSources
 * @package Alekseon\CustomFormsBuilder\Model
 */
class FieldOptionSources
{
    const DEFAULT_ATTRIBUTE_SOURCE_VALUE = 'default';
    /**
     * @var array
     */
    protected $optionSources;
    /**
     * @var
     */
    protected $optionSourcesByCodes;
    /**
     * @var
     */
    protected $codesBySourceModel;

    /**
     * FieldOptionSources constructor.
     */
    public function __construct(
        array $optionSources = []
    ) {
        $this->optionSources = $optionSources;
    }

    /**
     * @param $code
     */
    public function getOptionSourceByCode($code)
    {
        $optionsSources = $this->getOptionSources();
        return $optionsSources[$code] ?? false;
    }

    /**
     * @param $sourceModel
     * @return string | false
     */
    public function getCodeBySourceModel($sourceModel)
    {
        $this->getOptionSources();
        return $this->codesBySourceModel[$sourceModel] ?? false;
    }

    /**
     * @return array|null
     */
    public function getOptionSources()
    {
        if ($this->optionSourcesByCodes === null) {
            $this->optionSourcesByCodes = [];
            foreach ($this->optionSources as $code => $data) {
                $optionSource = new \Magento\Framework\DataObject($data);
                $sourceModel = $optionSource->getSourceModel();
                if (class_exists($sourceModel)) {
                    $optionSource->setCode($code);
                    $this->optionSourcesByCodes[$code] = $optionSource;
                    $this->codesBySourceModel[$sourceModel] = $code;
                }
            }
        }
        return $this->optionSourcesByCodes;
    }

    /**
     *
     */
    public function toOptionArray()
    {
        $options = $this->getOptionSources();
        $result = [[
            'value' => self::DEFAULT_ATTRIBUTE_SOURCE_VALUE,
            'label' => __('Default Attribute Options'),
        ]];

        foreach ($options as $code => $option) {
            $result[] = [
                'value' => $code,
                'label' => __($option->getLabel()),
            ];
        }
        return $result;
    }
}
