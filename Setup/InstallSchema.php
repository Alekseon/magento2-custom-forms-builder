<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\InstallSchemaInterface;
use Alekseon\AlekseonEav\Setup\EavSchemaSetupFactory;
use Magento\Framework\DB\Adapter\AdapterInterface;

/**
 * Class InstallSchema
 * @package Alekseon\CustomFormsBuilder\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @var EavSchemaSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * InstallSchema constructor.
     * @param EavSchemaSetupFactory $eavSetupFactory
     */
    public function __construct(
        EavSchemaSetupFactory $eavSetupFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $formTable = $setup->getConnection()
            ->newTable($setup->getTable('alekseon_custom_form'))
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'unsigned' => true, 'primary' => true],
                'Form Entity ID'
            );
        $setup->getConnection()->createTable($formTable);

        $formRecordTable = $setup->getConnection()
            ->newTable($setup->getTable('alekseon_custom_form_record'))
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'unsigned' => true, 'primary' => true],
                'Record Entity ID'
            )->addColumn(
                'form_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'Form Id'
            );
        $setup->getConnection()->createTable($formRecordTable);

        $eavSetup->createFullEavStructure(
            'alekseon_custom_form_attribute',
            'alekseon_custom_form_entity',
            null,
            'alekseon_custom_form',
            'entity_id'
        );

        $eavSetup->createFullEavStructure(
            'alekseon_custom_form_record_attribute',
            'alekseon_custom_form_record_entity',
            null,
            'alekseon_custom_form_record',
            'entity_id'
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('alekseon_custom_form_record_attribute'),
            'form_id',
            [
                'type' => Table::TYPE_INTEGER,
                'unsigned' => true,
                'nullable' => false,
                'comment' => 'Form Id'
            ]
        );

        $setup->getConnection()->addForeignKey(
            $setup->getConnection()->getForeignKeyName(
                $setup->getTable('alekseon_custom_form_record_attribute'),
                'form_id',
                $setup->getTable('alekseon_custom_form'),
                'entity_id'
            ),
            $setup->getTable('alekseon_custom_form_record_attribute'),
            'form_id',
            $setup->getTable('alekseon_custom_form'),
            'entity_id',
            AdapterInterface::FK_ACTION_CASCADE
        );

        $setup->getConnection()->addForeignKey(
            $setup->getConnection()->getForeignKeyName(
                $setup->getTable('alekseon_custom_form_record'),
                'form_id',
                $setup->getTable('alekseon_custom_form'),
                'entity_id'
            ),
            $setup->getTable('alekseon_custom_form_record'),
            'form_id',
            $setup->getTable('alekseon_custom_form'),
            'entity_id',
            AdapterInterface::FK_ACTION_CASCADE
        );
    }
}
