<?php

namespace Eadesigndev\ComposerRepo\Model;

use Eadesigndev\ComposerRepo\Api\Data\ComposerInterface;
use Eadesigndev\ComposerRepo\Api\PackagesRepositoryInterface;
use Eadesigndev\ComposerRepo\Model\ResourceModel\Collection\CollectionPackages;
use Eadesigndev\ComposerRepo\Model\ResourceModel\Collection\CollectionPackagesFactory;
use Eadesigndev\ComposerRepo\Api\Data\ComposerSearchResultsInterfaceFactory;
use Eadesigndev\ComposerRepo\Model\ResourceModel\Packages as PackagesResources;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException as Exception;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class PackagesRepository
 * @package Eadesigndev\ComposerRepo\Model
 */
class PackagesRepository implements PackagesRepositoryInterface
{
    /**
     * @var array
     */
    private $instances = [];
    /**
     * @var PackagesResources
     */
    private $resource;
    /**
     * @var ComposerInterface
     */
    private $composer;
    /**
     * @var PackagesFactory
     */
    private $packagesFactory;
    /**
     * @var ManagerInterface
     */
    private $messageManager;
    /**
     * @var CollectionPackagesFactory
     */
    private $collectionPackagesFactory;
    /**
     * @var ComposerSearchResultsInterfaceFactory
     */
    private $composerSearchResultsInterfaceFactory;

    /**
     * CustomerAuthRepository constructor.
     * @param PackagesResources $resource
     * @param ComposerInterface $composer
     * @param PackagesFactory $packagesFactory
     * @param ManagerInterface $messageManager
     * @param CollectionPackagesFactory $collectionPackagesFactory
     * @param ComposerSearchResultsInterfaceFactory $composerSearchResultsInterfaceFactory
     */
    public function __construct(
        PackagesResources $resource,
        ComposerInterface $composer,
        PackagesFactory $packagesFactory,
        ManagerInterface $messageManager,
        CollectionPackagesFactory $collectionPackagesFactory,
        ComposerSearchResultsInterfaceFactory $composerSearchResultsInterfaceFactory
    ) {
        $this->resource                              = $resource;
        $this->composer                              = $composer;
        $this->collectionPackagesFactory             = $collectionPackagesFactory;
        $this->packagesFactory                       = $packagesFactory;
        $this->messageManager                        = $messageManager;
        $this->composerSearchResultsInterfaceFactory = $composerSearchResultsInterfaceFactory;
    }

    /**
     * @param ComposerInterface $composer
     * @return ComposerInterface
     * @throws \Exception
     */
    public function save(ComposerInterface $composer)
    {
        try {
            $this->resource->save($composer);
        } catch (Exception $e) {
            $this->messageManager
                ->addExceptionMessage(
                    $e,
                    'There was a error while saving the package ' . $e->getMessage()
                );
        }

        return $composer;
    }
    /**
     * @param $composerId
     * @return array
     */
    public function getById($composerId)
    {
        if (!isset($this->instances[$composerId])) {
            $composer = $this->packagesFactory->create();
            $this->resource->load($composer, $composerId);
            $this->instances[$composerId] = $composer;
        }
        return $this->instances[$composerId];
    }
    /**
     * @param ComposerInterface $composer
     * @return bool
     * @throws \Exception
     */
    public function delete(ComposerInterface $composer)
    {
        $id = $composer->getEntityId();
        try {
            unset($this->instances[$id]);
            $this->resource->delete($composer);
        } catch (Exception $e) {
            $this->messageManager
                ->addExceptionMessage($e, 'There was a error while deleting the package');
        }
        unset($this->instances[$id]);
        return true;
    }
    /**
     * @param $composerId
     * @return bool
     * @throws \Exception
     */
    public function deleteById($composerId)
    {
        $composer = $this->getById($composerId);
        return $this->delete($composer);
    }
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionPackagesFactory->create();

        $this->addFiltersToCollection($searchCriteria, $collection);
        $this->addSortOrdersToCollection($searchCriteria, $collection);
        $this->addPagingToCollection($searchCriteria, $collection);

        $collection->load();

        return $this->buildSearchResult($searchCriteria, $collection);
    }
    private function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, CollectionPackages $collection)
    {
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $fields[] = $filter->getField();
                $conditions[] = [$filter->getConditionType() => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }
    }

    private function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, CollectionPackages $collection)
    {
        foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }

    private function addPagingToCollection(SearchCriteriaInterface $searchCriteria, CollectionPackages $collection)
    {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }

    private function buildSearchResult(SearchCriteriaInterface $searchCriteria, CollectionPackages $collection)
    {
        $searchResults = $this->composerSearchResultsInterfaceFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
