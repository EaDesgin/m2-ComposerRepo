<?php

namespace Eadesigndev\ComposerRepo\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface ComposerSearchResultsInterface extends SearchResultsInterface
{
    public function getItems();

    public function setItems(array $items);
}