<?php

namespace Eadesigndev\ComposerRepo\Model\Packages;

use Eadesigndev\ComposerRepo\Api\Data\ComposerInterface;
use Eadesigndev\ComposerRepo\Api\VersionRepositoryInterface;
use Eadesigndev\ComposerRepo\Model\ResourceModel\Collection\CollectionVersion;
use Eadesigndev\ComposerRepo\Model\ResourceModel\Collection\CollectionVersionFactory;
use Eadesigndev\ComposerRepo\Api\Data\ComposerSearchResultsInterfaceFactory;
use Eadesigndev\ComposerRepo\Model\VersionFactory;
use Eadesigndev\ComposerRepo\Model\ResourceModel\Packages\Notify as NotifyResorceModel;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException as Exception;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class VersionRepository
 * @package Eadesigndev\ComposerRepo\Model\Packages
 */
class VersionRepository implements VersionRepositoryInterface
{
    /**
     * @var array
     */
    private $instances = [];
    /**
     * @var NotifyResorceModel
     */
    private $resource;
    /**
     * @var ComposerInterface
     */
    private $composer;
    /**
     * @var VersionFactory
     */
    private $versionFactory;
    /**
     * @var ManagerInterface
     */
    private $messageManager;
    /**
     * @var CollectionVersionFactory
     */
    private $collectionVersionFactory;
    /**
     * @var ComposerSearchResultsInterfaceFactory
     */
    private $composerSearchResultsInterfaceFactory;

    /**
     * CustomerPackagesRepository constructor.
     * @param NotifyResorceModel $resource
     * @param ComposerInterface $composer
     * @param CollectionVersionFactory $collectionVersionFactory
     * @param ComposerSearchResultsInterfaceFactory $composerSearchResultsInterfaceFactory
     * @param VersionFactory $versionFactory
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        NotifyResorceModel $resource,
        ComposerInterface $composer,
        CollectionVersionFactory $collectionVersionFactory,
        ComposerSearchResultsInterfaceFactory $composerSearchResultsInterfaceFactory,
        VersionFactory $versionFactory,
        ManagerInterface $messageManager
    ) {
        $this->resource                              = $resource;
        $this->composer                              = $composer;
        $this->collectionVersionFactory              = $collectionVersionFactory;
        $this->versionFactory                        = $versionFactory;
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
            $composer = $this->versionFactory->create();
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
        $collection = $this->collectionVersionFactory->create();

        $this->addFiltersToCollection($searchCriteria, $collection);
        $this->addSortOrdersToCollection($searchCriteria, $collection);
        $this->addPagingToCollection($searchCriteria, $collection);

        $collection->load();

        return $this->buildSearchResult($searchCriteria, $collection);
    }
    private function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, CollectionVersion $collection)
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

    private function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, CollectionVersion $collection)
    {
        foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }

    private function addPagingToCollection(SearchCriteriaInterface $searchCriteria, CollectionVersion $collection)
    {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }

    private function buildSearchResult(SearchCriteriaInterface $searchCriteria, CollectionVersion $collection)
    {
        $searchResults = $this->composerSearchResultsInterfaceFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
