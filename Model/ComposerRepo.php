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

    public function setId($entityId)
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
}