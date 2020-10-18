<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Setup\Patch\Schema;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class FormRecordCreatedAtColumn
 * @package Alekseon\CustomFormsBuilder\Setup\Patch
 */
class FormRecordCreatedAtColumn implements SchemaPatchInterface
{
    /**
     * @var SchemaSetupInterface
     */
    private $schemaSetup;

    /**
     * EnableSegmentation constructor.
     *
     * @param SchemaSetupInterface $schemaSetup
     */
    public function __construct(
        SchemaSetupInterface $schemaSetup
    ) {
        $this->schemaSetup = $schemaSetup;
    }

    /**
     * @return SchemaPatchInterface|void
     */
    public function apply()
    {
        $setup = $this->schemaSetup->startSetup();

        $setup->getConnection()->addColumn(
            'alekseon_custom_form_record',
            'created_at',
            [
                'type' => Table::TYPE_TIMESTAMP,
                'nullable' => false,
                'comment' => 'Created At',
                'default' => Table::TIMESTAMP_INIT
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
