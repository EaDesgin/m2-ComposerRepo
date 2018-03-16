<?php

namespace Eadesigndev\ComposerRepo\Model\Packages;

use Eadesigndev\ComposerRepo\Api\Data\ComposerInterface;
use Eadesigndev\ComposerRepo\Api\NotifyRepositoryInterface;
use Eadesigndev\ComposerRepo\Model\ResourceModel\Collection\CollectionNotify;
use Eadesigndev\ComposerRepo\Model\ResourceModel\Collection\CollectionNotifyFactory;
use Eadesigndev\ComposerRepo\Api\Data\ComposerSearchResultsInterfaceFactory;
use Eadesigndev\ComposerRepo\Model\NotifyFactory;
use Eadesigndev\ComposerRepo\Model\ResourceModel\Packages\Notify as NotifyResorceModel;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException as Exception;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class NotifyRepository
 * @package Eadesigndev\ComposerRepo\Model\Packages
 */
class NotifyRepository implements NotifyRepositoryInterface
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
     * @var NotifyFactory
     */
    private $notifyFactory;
    /**
     * @var ManagerInterface
     */
    private $messageManager;
    /**
     * @var CollectionNotifyFactory
     */
    private $collectionNotifyFactory;
    /**
     * @var ComposerSearchResultsInterfaceFactory
     */
    private $composerSearchResultsInterfaceFactory;

    /**
     * CustomerPackagesRepository constructor.
     * @param NotifyResorceModel $resource
     * @param ComposerInterface $composer
     * @param CollectionNotifyFactory $collectionNotifyFactory
     * @param ComposerSearchResultsInterfaceFactory $composerSearchResultsInterfaceFactory
     * @param NotifyFactory $notifyFactory
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        NotifyResorceModel $resource,
        ComposerInterface $composer,
        CollectionNotifyFactory $collectionNotifyFactory,
        ComposerSearchResultsInterfaceFactory $composerSearchResultsInterfaceFactory,
        NotifyFactory $notifyFactory,
        ManagerInterface $messageManager
    ) {
        $this->resource                              = $resource;
        $this->composer                              = $composer;
        $this->collectionNotifyFactory               = $collectionNotifyFactory;
        $this->notifyFactory                         = $notifyFactory;
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
            $composer = $this->notifyFactory->create();
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
        $collection = $this->collectionNotifyFactory->create();

        $this->addFiltersToCollection($searchCriteria, $collection);
        $this->addSortOrdersToCollection($searchCriteria, $collection);
        $this->addPagingToCollection($searchCriteria, $collection);

        $collection->load();

        return $this->buildSearchResult($searchCriteria, $collection);
    }
    private function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, CollectionNotify $collection)
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

    private function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, CollectionNotify $collection)
    {
        foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }

    private function addPagingToCollection(SearchCriteriaInterface $searchCriteria, CollectionNotify $collection)
    {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }

    private function buildSearchResult(SearchCriteriaInterface $searchCriteria, CollectionNotify $collection)
    {
        $searchResults = $this->composerSearchResultsInterfaceFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}