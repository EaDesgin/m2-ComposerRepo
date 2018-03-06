<?php

namespace Eadesigndev\ComposerRepo\Model\Customer;

use Eadesigndev\ComposerRepo\Model\ResourceModel\Customer\CustomerAuth as ResourceCustomerAuth;
use Magento\Framework\Model\AbstractModel;

class CustomerAuth extends AbstractModel
{
    const CACHE_KEY = 'composer_repo_customer_key_';
    const CACHE_TAG = 'composer_repo_customer_';
    /**
     * Identifier for history item
     */
    const ENTITY              = 'composerrepo';

    /**
     * Event type names for order emails
     */
    const EMAIL_EVENT_NAME    = 'installation_details';

    protected function _construct()
    {
        $this->_init(ResourceCustomerAuth::class);
    }
}