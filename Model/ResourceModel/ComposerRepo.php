<?php

namespace Eadesigndev\ComposerRepo\Model\ResourceModel;

use Eadesigndev\ComposerRepo\Api\Data\ComposerInterface;
use Eadesigndev\ComposerRepo\Setup\InstallSchema;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Class ComposerRepo
 * @package Eadesigndev\ComposerRepo\Model\ResourceModel
 */
class ComposerRepo extends AbstractDb
{
    /**
     * ComposerRepo constructor.
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
        $this->_init(InstallSchema::TABLE, ComposerInterface::ENTITY_ID);
    }
}
