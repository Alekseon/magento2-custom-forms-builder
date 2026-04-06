<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Setup\Patch\Schema;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class CreateFrontendNotesTable implements SchemaPatchInterface
{
    /**
     * @var SchemaSetupInterface
     */
    private $schemaSetup;

    /**
     * @param SchemaSetupInterface $schemaSetup
     */
    public function __construct(
        SchemaSetupInterface $schemaSetup
    ) {
        $this->schemaSetup = $schemaSetup;
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        $this->schemaSetup->startSetup();

        $attributeTableName = 'alekseon_custom_form_record_attribute';
        $attributeFrontendNotesTableName = $attributeTableName . '_frontend_note';

        if ($this->schemaSetup->tableExists($this->schemaSetup->getTable($attributeFrontendNotesTableName))) {
            return;
        }

        $attributeFrontendNotesTable = $this->schemaSetup->getConnection()->newTable(
            $this->schemaSetup->getTable($attributeFrontendNotesTableName)
        )->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Id'
        )->addColumn(
            'attribute_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Attribute ID'
        )->addColumn(
            'store_id',
            Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Store Id'
        )->addColumn(
            'note',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true, 'default' => null],
            'Note'
        )->addIndex(
            $this->schemaSetup->getIdxName($attributeFrontendNotesTableName, ['attribute_id']),
            ['attribute_id']
        )->addIndex(
            $this->schemaSetup->getIdxName($attributeFrontendNotesTableName, ['store_id']),
            ['store_id']
        )->addForeignKey(
            $this->schemaSetup->getFkName($attributeFrontendNotesTableName, 'attribute_id', $attributeTableName, 'id'),
            'attribute_id',
            $this->schemaSetup->getTable($attributeTableName),
            'id',
            Table::ACTION_CASCADE
        )->addForeignKey(
            $this->schemaSetup->getFkName($attributeFrontendNotesTableName, 'store_id', 'store', 'store_id'),
            'store_id',
            $this->schemaSetup->getTable('store'),
            'store_id',
            Table::ACTION_CASCADE
        )->setComment(
            'Alekseon Custom Form Record Attribute Frontend Notes'
        );
        $this->schemaSetup->getConnection()->createTable($attributeFrontendNotesTable);

        $this->schemaSetup->endSetup();
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [
            \Alekseon\CustomFormsBuilder\Setup\Patch\Schema\CreateEavTablesV2::class
        ];
    }
}
