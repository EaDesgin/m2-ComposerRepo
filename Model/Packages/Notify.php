<?php

namespace Eadesigndev\ComposerRepo\Model\Packages;

use Eadesigndev\ComposerRepo\Model\ResourceModel\Packages\Notify as NotifyResource;
use Magento\Framework\Model\AbstractModel;

class Notify extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(NotifyResource::class);
    }
}