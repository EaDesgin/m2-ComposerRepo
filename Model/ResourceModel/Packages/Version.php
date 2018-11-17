<?php

namespace Eadesigndev\ComposerRepo\Model\ResourceModel\Packages;

use Eadesigndev\ComposerRepo\Api\Data\ComposerInterface;
use Eadesigndev\ComposerRepo\Setup\InstallSchema;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Class Version
 * @package Eadesigndev\ComposerRepo\Model\ResourceModel\Packages
 */
class Version extends AbstractDb
{
    /**
     * Version constructor.
     * @param Context $context
     * @param string|null $connectionName
     */
    public function __construct(
        Context $context,
        string $connectionName = null
    ) {
        parent::__construct(
            $context,
            $connectionName
        );
    }

    // @codingStandardsIgnoreLine
    public function _construct()
    {
        $this->_init(InstallSchema::TABLE_PACKAGES_VERSIONS, ComposerInterface::ENTITY_ID);
    }
}
