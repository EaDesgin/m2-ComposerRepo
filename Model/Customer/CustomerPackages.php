<?php

namespace Eadesigndev\ComposerRepo\Model\Customer;

use Magento\Framework\Model\AbstractModel;

class CustomerPackages extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('Eadesigndev\ComposerRepo\Model\ResourceModel\Customer\CustomerPackages');
    }
}