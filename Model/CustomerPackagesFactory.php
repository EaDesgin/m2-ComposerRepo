<?php

namespace Eadesigndev\ComposerRepo\Model;

use Eadesigndev\ComposerRepo\Api\Data\ComposerInterface;
use Magento\Framework\ObjectManagerInterface;

class CustomerPackagesFactory implements CustomerPackageFactoryInterface
{
    /**
     * Object Manager instance
     *
     * @var ObjectManagerInterface
     */
    private $objectManager = null;

    /**
     * Instance name to create
     *
     * @var string
     */
    private $instanceName = null;

    /**
     * CustomerPackagesFactory constructor.
     * @param ObjectManagerInterface $objectManager
     * @param $instanceName
     */
    public function __construct(ObjectManagerInterface $objectManager, $instanceName = ComposerInterface::class)
    {
        $this->objectManager = $objectManager;
        $this->instanceName = $instanceName;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data = [])
    {
        return $this->objectManager->create($this->instanceName, $data);
    }
}