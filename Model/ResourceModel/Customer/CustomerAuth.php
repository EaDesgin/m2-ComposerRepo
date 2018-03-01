<?php

namespace Eadesigndev\ComposerRepo\Model\ResourceModel\Customer;

use Eadesigndev\ComposerRepo\Api\Data\ComposerInterface;
use Eadesigndev\ComposerRepo\Setup\InstallSchema;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Class CustomerAuth
 * @package Eadesigndev\ComposerRepo\Model\ResourceModel\Customer
 */
class CustomerAuth extends AbstractDb
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
        $this->_init(InstallSchema::TABLE_AUTH, ComposerInterface::ENTITY_ID);
    }

    public function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if (!$object->getId()) {
            $object->setCreatedate(now())
                ->setStatus(1);
            $this->generateUniqueAuthKey($object);
        }

        return parent::_beforeSave($object);
    }

    protected function generateUniqueAuthKey(\Magento\Framework\Model\AbstractModel $object)
    {
        while (($newKey = $this->getUniqueAuthKey()) === false) {
            // Key not unique, try another one
        }

        $object->setAuthKey($newKey)
            ->setAuthSecret(Mage::helper('core')->getRandomString(32));
        return $object;
    }
}