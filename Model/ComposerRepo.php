<?php

namespace Eadesigndev\ComposerRepo\Model;

use Eadesigndev\ComposerRepo\Api\Data\ComposerInterface;
use Eadesigndev\ComposerRepo\Model\ResourceModel\ComposerRepo as ComposerRepoResourceModel;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource as AbstractResourceModel;
use Magento\Framework\Registry;

class ComposerRepo extends AbstractModel implements ComposerInterface
{
    public function __construct(
        Context $context,
        Registry $registry,
        AbstractResourceModel $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
    }

    // @codingStandardsIgnoreLine
    public function _construct()
    {
        $this->_init(ComposerRepoResourceModel::class);
    }

    public function getEntityId()
    {
        return $this->getData(ComposerInterface::ENTITY_ID);
    }

    public function getCreatedAt()
    {
        return $this->getData(ComposerInterface::CREATED_AT);
    }

    public function getStatus()
    {
        return $this->getData(ComposerInterface::STATUS);
    }

    public function getProductId()
    {
        return $this->getData(ComposerInterface::PRODUCT_ID);
    }

    public function getName()
    {
        return $this->getData(ComposerInterface::NAME);
    }

    public function getDescription()
    {
        return $this->getData(ComposerInterface::DESCRIPTION);
    }

    public function getRepositoryUrl()
    {
        return $this->getData(ComposerInterface::REPOSITORY_URL);
    }

    public function getRepositoryOption()
    {
        return $this->getData(ComposerInterface::REPOSITORY_OPTIONS);
    }

    public function getPackageJson()
    {
        return $this->getData(ComposerInterface::PACKAGE_JSON);
    }

    public function getVersion()
    {
        return $this->getData(ComposerInterface::VERSION);
    }

    public function getBundledPackage()
    {
        return $this->getData(ComposerInterface::BUNDLED_PACKAGE);
    }

    public function getDefault()
    {
        return $this->getData(ComposerInterface::DEFAULT);
    }

    public function getCustomerId()
    {
        return $this->getData(ComposerInterface::CUSTOMER_ID);
    }

    public function getAuthKey()
    {
        return $this->getData(ComposerInterface::AUTH_KEY);
    }

    public function getAuthSecret()
    {
        return $this->getData(ComposerInterface::AUTH_SECRET);
    }

    public function getOrderId()
    {
        return $this->getData(ComposerInterface::ORDER_ID);
    }

    public function getPackageId()
    {
        return $this->getData(ComposerInterface::PACKAGE_ID);
    }

    public function getLastAllowedVersion()
    {
        return $this->getData(ComposerInterface::LAST_ALLOWED_VERSION);
    }

    public function getLastAllowedDate()
    {
        return $this->getData(ComposerInterface::LAST_ALLOWED_DATE);
    }

    public function getVersionId()
    {
        return $this->getData(ComposerInterface::VERSION_ID);
    }

    public function getRemoteIp()
    {
        return $this->getData(ComposerInterface::REMOTE_IP);
    }

    public function getFile()
    {
        return $this->getData(ComposerInterface::FILE);
    }

    public function setEntityId($entityId)
    {
        $this->setData(ComposerInterface::ENTITY_ID, $entityId);
    }

    public function setCreatedAt($createdAt)
    {
        $this->setData(ComposerInterface::CREATED_AT, $createdAt);
    }

    public function setStatus($status)
    {
        $this->setData(ComposerInterface::STATUS, $status);
    }

    public function setProductId($productId)
    {
        $this->setData(ComposerInterface::PRODUCT_ID, $productId);
    }

    public function setName($name)
    {
        $this->setData(ComposerInterface::NAME, $name);
    }

    public function setDescription($description)
    {
        $this->setData(ComposerInterface::DESCRIPTION, $description);
    }

    public function setRepositoryUrl($repositoryUrl)
    {
        $this->setData(ComposerInterface::REPOSITORY_URL, $repositoryUrl);
    }

    public function setRepositoryOption($repositoryOption)
    {
        $this->setData(ComposerInterface::REPOSITORY_OPTIONS, $repositoryOption);
    }

    public function setPackageJson($packageJson)
    {
        $this->setData(ComposerInterface::PACKAGE_JSON, $packageJson);
    }

    public function setVersion($version)
    {
        $this->setData(ComposerInterface::VERSION, $version);
    }

    public function setBundledPackage($bundledPackage)
    {
        $this->setData(ComposerInterface::BUNDLED_PACKAGE, $bundledPackage);
    }

    public function setDefault($default)
    {
        $this->setData(ComposerInterface::DEFAULT, $default);
    }

    public function setCustomerId($customerId)
    {
        $this->setData(ComposerInterface::CUSTOMER_ID, $customerId);
    }

    public function setAuthKey($authKey)
    {
        $this->setData(ComposerInterface::AUTH_KEY, $authKey);
    }

    public function setAuthSecret($authSecret)
    {
        $this->setData(ComposerInterface::AUTH_SECRET, $authSecret);
    }

    public function setOrderId($orderId)
    {
        $this->setData(ComposerInterface::ORDER_ID, $orderId);
    }

    public function setPackageId($packageId)
    {
        $this->setData(ComposerInterface::PACKAGE_ID, $packageId);
    }

    public function setLastAllowedVersion($lastAllowedVersion)
    {
        $this->setData(ComposerInterface::LAST_ALLOWED_VERSION, $lastAllowedVersion);
    }

    public function setLastAllowedDate($lastAllowedDate)
    {
        $this->setData(ComposerInterface::LAST_ALLOWED_DATE, $lastAllowedDate);
    }

    public function setVersionId($versionId)
    {
        $this->setData(ComposerInterface::VERSION_ID, $versionId);
    }

    public function setRemoteIp($remoteIp)
    {
        $this->setData(ComposerInterface::REMOTE_IP, $remoteIp);
    }

    public function setFile($file)
    {
        $this->setData(ComposerInterface::FILE, $file);
    }
}
