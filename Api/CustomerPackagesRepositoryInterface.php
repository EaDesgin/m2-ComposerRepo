<?php

namespace Eadesigndev\ComposerRepo\Api;

use Eadesigndev\ComposerRepo\Api\Data\ComposerInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface CustomerPackagesRepositoryInterface
{
    public function save(ComposerInterface $templates);

    public function getById($value);

    public function delete(ComposerInterface $templates);

    public function deleteById($value);

    public function getList(SearchCriteriaInterface $searchCriteria);

}