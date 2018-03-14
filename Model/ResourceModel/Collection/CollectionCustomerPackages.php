<?php

namespace Eadesigndev\ComposerRepo\Model\ResourceModel\Collection;

use Eadesigndev\ComposerRepo\Model\Customer\CustomerPackages;
use Eadesigndev\ComposerRepo\Model\ResourceModel\Customer\CustomerPackages as CustomerPackagesResource;

class CollectionCustomerPackages extends AbstractCollection
{
    /**
     * @var string
     */
    //@codingStandardsIgnoreLine
    protected $_idCustomerAuth = 'entity_id';

    /**
     * Init resource model
     * @return void
     */
    public function _construct()
    {

        $this->_init(
            CustomerPackages::class,
            CustomerPackagesResource::class
        );

        $this->_map['composer']['entity_id'] = 'main_table.entity_id';
    }

    /**
     * Add filter by store
     *
     * @param int|array|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        return $this;
    }
}
