<?php

namespace Eadesigndev\ComposerRepo\Model\ResourceModel\Collection;

use Eadesigndev\ComposerRepo\Model\ComposerRepo as ComposerRepoModel;
use Eadesigndev\ComposerRepo\Model\ResourceModel\ComposerRepo  as ComposerRepoResourceModel;

class Collection extends AbstractCollection
{

    /**
     * @var string
     */
    //@codingStandardsIgnoreLine
    protected $_idComposerName = 'entity_id';

    /**
     * Init resource model
     * @return void
     */
    // @codingStandardsIgnoreLine
    public function _construct()
    {

        $this->_init(
            ComposerRepoModel::class,
            ComposerRepoResourceModel::class
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
