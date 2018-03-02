<?php

namespace Eadesigndev\ComposerRepo\Model;

use Magento\Framework\Model\AbstractModel;

class Packages extends AbstractModel
{
    const CACHE_TAG = 'PACKAGES_JSON';
    const CACHE_KEY = 'composer_repo_package_';

    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;

    const PACKAGE_NORMAL = 0;
    const PACKAGE_BUNDLE = 1;

    protected function _construct()
    {
        $this->_init('Eadesigndev\ComposerRepo\Model\ResourceModel\Packages');
    }

    public function getByPackageName($name)
    {
        return $this->load($name, 'name');
    }
}