<?php

namespace Eadesigndev\ComposerRepo\Model\Customer;

use Eadesigndev\ComposerRepo\Model\ResourceModel\Customer\CustomerAuth as CustomerAuthResource;
use Magento\Framework\Model\AbstractModel;

class CustomerAuth extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(CustomerAuthResource::class);
    }
}