<?php

namespace Eadesigndev\ComposerRepo\Model\Packages;

use Eadesigndev\ComposerRepo\Model\ResourceModel\Packages\Version as VersionResource;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Version
 * @package Eadesigndev\ComposerRepo\Model\Packages
 */
class Version extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(VersionResource::class);
    }

}