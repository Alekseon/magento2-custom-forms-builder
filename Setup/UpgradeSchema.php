<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * Class UpgradeSchema
 * @package Alekseon\CustomFormsBuilder\Setup
 */
class UpgradeSchema extends \Alekseon\AlekseonEav\Setup\UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable('alekseon_custom_form_record'),
                'created_at',
                [
                    'type' => Table::TYPE_TIMESTAMP,
                    'comment' => 'Creation Time',
                    'DEFAULT' =>  Table::TIMESTAMP_INIT
                ]
            );
        }

        if (version_compare($context->getVersion(), '1.0.4', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable('alekseon_custom_form'),
                'created_at',
                [
                    'type' => Table::TYPE_TIMESTAMP,
                    'comment' => 'Creation Time',
                    'DEFAULT' =>  Table::TIMESTAMP_INIT
                ]
            );
        }

        if ($context->getVersion() && version_compare($context->getVersion(), '1.0.5', '<')) {
            $this->updateAttributeCodeColumnSize($setup, 'alekseon_custom_form_attribute');
            $this->updateAttributeCodeColumnSize($setup, 'alekseon_custom_form_record_attribute');
        }

        if (version_compare($context->getVersion(), '1.0.6', '<')) {
            $this->addCodeFieldToFormAndRecord($setup);
        }

        $setup->endSetup();
    }

    /**
     * @param $setup
     */
    protected function addCodeFieldToFormAndRecord(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('alekseon_custom_form'),
            'form_code',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'comment' => 'Form Code',
                'nullable' => true,
            ]
        );
        $setup->getConnection()->addIndex(
            $setup->getTable('alekseon_custom_form'),
            $setup->getIdxName(
                'alekseon_custom_form',
                ['form_code'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['form_code'],
            \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
        );
        $setup->getConnection()->addColumn(
            $setup->getTable('alekseon_custom_form_record_attribute'),
            'field_code',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'comment' => 'Field Code',
                'nullable' => true,
            ]
        );
       $setup->getConnection()->addIndex(
            $setup->getTable('alekseon_custom_form_record_attribute'),
            $setup->getIdxName(
                'alekseon_custom_form_record_attribute',
                ['field_code', 'form_id'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['field_code', 'form_id'],
            \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
        );
    }
}
