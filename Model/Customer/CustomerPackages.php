<?php

namespace Eadesigndev\ComposerRepo\Model\Customer;

use Eadesigndev\ComposerRepo\Model\ResourceModel\Customer\CustomerPackages as ResourceCustomerPackages;
use Magento\Framework\Model\AbstractModel;

class CustomerPackages extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(ResourceCustomerPackages::class);
    }
}