<?php

namespace Eadesigndev\ComposerRepo\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

//@codingStandardsIgnoreFile

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    const TABLE = 'eadesign_composerrepo_packages';

    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $composerTable = $setup->getTable(self::TABLE);

        if (!$setup->tableExists($composerTable)) {
            $table = $setup->getConnection()->newTable(
                $setup->getTable(self::TABLE)
            )->addColumn(
                'entity_id',
                Table::TYPE_SMALLINT,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                ],
                'Entity Id'
            )->addColumn(
                'createdate',
                Table::TYPE_TIMESTAMP,
                null,
                [],
                'Create date'
            )->addColumn(
                'status',
                Table::TYPE_INTEGER,
                null,
                [],
                'Status'
            )->addColumn(
                'product_id',
                Table::TYPE_INTEGER,
                null,
                [],
                'Product Id'
            )->addColumn(
                'name',
                Table::TYPE_TEXT,
                50,
                [],
                'Package Name'
            )->addColumn(
                'description',
                Table::TYPE_TEXT,
                50,
                [],
                'Package description'
            )->addColumn(
                'repository_url',
                Table::TYPE_TEXT,
                150,
                [],
                'Repository URL'
            )->addColumn(
                'repository_options',
                Table::TYPE_TEXT,
                null,
                [],
                'Repository Options JSON'
            )->addColumn(
                'package_json',
                Table::TYPE_TEXT,
                null,
                [],
                'Generated package JSON'
            )->addColumn(
                'version',
                Table::TYPE_TEXT,
                50,
                [],
                'Last version number'
            )->addColumn(
                'bundled_package',
                Table::TYPE_SMALLINT,
                0,
                [],
                'Bundled package (always available)'
            );
            $setup->getConnection()->createTable($table);
        }
        $setup->endSetup();
    }

}