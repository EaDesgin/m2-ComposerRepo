<?php

namespace Eadesigndev\ComposerRepo\Model\ResourceModel\Customer;

use Eadesigndev\ComposerRepo\Api\Data\ComposerInterface;
use Eadesigndev\ComposerRepo\Setup\InstallSchema;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Class CustomerPackages
 * @package Eadesigndev\ComposerRepo\Model\ResourceModel\Customer
 */
class CustomerPackages extends AbstractDb
{
    /**
     * CustomerPackages constructor.
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

    public function _construct()
    {
        $this->_init(InstallSchema::TABLE_CUSTOMER_PACKAGE, ComposerInterface::ENTITY_ID);
    }

    public function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if (!$object->getId()) {
            $object->setCreatedate(now());
        }

        return parent::_beforeSave($object);
    }
}