<?php

namespace Eadesigndev\ComposerRepo\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

//@codingStandardsIgnoreFile

/**
 * @codeCoverageIgnore
 * @SuppressWarnings(PHPMD)
 */
class InstallSchema implements InstallSchemaInterface
{
    const TABLE = 'eadesign_composerrepo_packages';
    const TABLE_AUTH = 'eadesign_composerrepo_customer_auth';
    const TABLE_CUSTOMER_PACKAGE = 'eadesign_composerrepo_customer_packages';
    const TABLE_PACKAGES_NOTIFY = 'eadesign_composerrepo_packages_notify';
    const TABLE_PACKAGES_VERSIONS = 'eadesign_composerrepo_packages_versions';

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
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Created At'
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

        $composerAuthTable = $setup->getTable(self::TABLE_AUTH);

        if (!$setup->tableExists($composerAuthTable)) {
            $table = $setup->getConnection()->newTable(
                $setup->getTable(self::TABLE_AUTH)
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
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Created At'
            )->addColumn(
                'status',
                Table::TYPE_INTEGER,
                null,
                [],
                'Status'
            )->addColumn(
                'default',
                Table::TYPE_INTEGER,
                null,
                [],
                'default'
            )->addColumn(
                'customer_id',
                Table::TYPE_INTEGER,
                null,
                [],
                'Customer ID'
            )->addColumn(
                'description',
                Table::TYPE_TEXT,
                50,
                [],
                'Key description'
            )->addColumn(
                'auth_key',
                Table::TYPE_TEXT,
                50,
                [],
                'Auth Key'
            )->addColumn(
                'auth_secret',
                Table::TYPE_TEXT,
                50,
                [],
                'Auth Secret'
            );
            $setup->getConnection()->createTable($table);
        }
        $setup->endSetup();

        $customerPackageTable = $setup->getTable(self::TABLE_CUSTOMER_PACKAGE);

        if (!$setup->tableExists($customerPackageTable)) {
            $table = $setup->getConnection()->newTable(
                $setup->getTable(self::TABLE_CUSTOMER_PACKAGE)
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
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Created At'
            )->addColumn(
                'status',
                Table::TYPE_INTEGER,
                null,
                [],
                'Status'
            )->addColumn(
                'customer_id',
                Table::TYPE_INTEGER,
                null,
                [],
                'Customer ID'
            )->addColumn(
                'order_id',
                Table::TYPE_INTEGER,
                null,
                [],
                'Order ID'
            )->addColumn(
                'package_id',
                Table::TYPE_INTEGER,
                null,
                [],
                'Package ID'
            )->addColumn(
                'last_allowed_version',
                Table::TYPE_TEXT,
                50,
                [],
                'Last version number allowed'
            )->addColumn(
                'last_allowed_date',
                Table::TYPE_DATETIME,
                null,
                [],
                'Last product update date'
            );
            $setup->getConnection()->createTable($table);
        }
        $setup->endSetup();

        $packagesNotify = $setup->getTable(self::TABLE_PACKAGES_NOTIFY);

        if (!$setup->tableExists($packagesNotify)) {
            $table = $setup->getConnection()->newTable(
                $setup->getTable(self::TABLE_PACKAGES_NOTIFY)
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
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Created At'
            )->addColumn(
                'customer_id',
                Table::TYPE_INTEGER,
                null,
                [],
                'Customer ID'
            )->addColumn(
                'package_id',
                Table::TYPE_INTEGER,
                null,
                [],
                'Package ID'
            )->addColumn(
                'version_id',
                Table::TYPE_INTEGER,
                null,
                [],
                'Version'
            )->addColumn(
                'remote_ip',
                Table::TYPE_TEXT,
                64,
                [],
                'Remote IP'
            );
            $setup->getConnection()->createTable($table);
        }
        $setup->endSetup();

        $packagesVersions = $setup->getTable(self::TABLE_PACKAGES_VERSIONS);

        if (!$setup->tableExists($packagesVersions)) {
            $table = $setup->getConnection()->newTable(
                $setup->getTable(self::TABLE_PACKAGES_VERSIONS)
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
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Created At'
            )->addColumn(
                'package_id',
                Table::TYPE_INTEGER,
                null,
                [],
                'Package ID'
            )->addColumn(
                'version',
                Table::TYPE_TEXT,
                50,
                [],
                'Package Version'
            )->addColumn(
                'file',
                Table::TYPE_TEXT,
                250,
                [],
                'File Name'
            );
            $setup->getConnection()->createTable($table);
        }
        $setup->endSetup();
    }
}