<?php

namespace Eadesigndev\ComposerRepo\Model\ResourceModel\Collection;

use Eadesigndev\ComposerRepo\Model\Packages;
use Eadesigndev\ComposerRepo\Model\ResourceModel\Packages as PackagesResource;

class CollectionPackages extends AbstractCollection
{
    /**
     * @var string
     */
    //@codingStandardsIgnoreLine
    protected $_idPackages = 'entity_id';

    /**
     * Init resource model
     * @return void
     */
    public function _construct()
    {

        $this->_init(
            Packages::class,
            PackagesResource::class
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
