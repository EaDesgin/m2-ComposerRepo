<?php
/**
 * Copyright © 2018 EaDesign by Eco Active S.R.L. All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\ComposerRepo\Api;

use Eadesigndev\ComposerRepo\Api\Data\ComposerInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface PackagesRepositoryInterface
{
    public function save(ComposerInterface $templates);

    public function getById($value);

    public function delete(ComposerInterface $templates);

    public function deleteById($value);

    public function getList(SearchCriteriaInterface $searchCriteria);
}
