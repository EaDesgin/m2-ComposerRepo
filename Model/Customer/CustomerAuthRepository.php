<?php

namespace Eadesigndev\ComposerRepo\Model\Customer;

use Eadesigndev\ComposerRepo\Api\Data\ComposerInterface;
use Eadesigndev\ComposerRepo\Api\CustomerAuthRepositoryInterface;
use Eadesigndev\ComposerRepo\Model\ResourceModel\Collection\Collection;
use Eadesigndev\ComposerRepo\Model\ResourceModel\Collection\CollectionFactory;
use Eadesigndev\ComposerRepo\Model\CustomerAuthFactory;
use Eadesigndev\ComposerRepo\Api\Data\ComposerSearchResultsInterfaceFactory;
use Eadesigndev\ComposerRepo\Model\ResourceModel\Customer\CustomerAuth as CustomerAuthResourceModel;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException as Exception;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class CustomerAuthRepository
 * @package Eadesigndev\ComposerRepo\Model\Customer
 */
class CustomerAuthRepository implements CustomerAuthRepositoryInterface
{
    /**
     * @var array
     */
    private $instances = [];
    /**
     * @var CustomerAuthResourceModel
     */
    private $resource;
    /**
     * @var ComposerInterface
     */
    private $composer;
    /**
     * @var CustomerAuthFactory
     */
    private $customerAuthFactory;
    /**
     * @var ManagerInterface
     */
    private $messageManager;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var ComposerSearchResultsInterfaceFactory
     */
    private $composerSearchResultsInterfaceFactory;

    /**
     * CustomerAuthRepository constructor.
     * @param CustomerAuthResourceModel $resource
     * @param ComposerInterface $composer
     * @param CustomerAuthFactory $customerAuthFactory
     * @param ManagerInterface $messageManager
     * @param CollectionFactory $collectionFactory
     * @param ComposerSearchResultsInterfaceFactory $composerSearchResultsInterfaceFactory
     */
    public function __construct(
        CustomerAuthResourceModel $resource,
        ComposerInterface $composer,
        CustomerAuthFactory $customerAuthFactory,
        ManagerInterface $messageManager,
        CollectionFactory $collectionFactory,
        ComposerSearchResultsInterfaceFactory $composerSearchResultsInterfaceFactory
    ) {
        $this->resource                              = $resource;
        $this->composer                              = $composer;
        $this->collectionFactory                     = $collectionFactory;
        $this->customerAuthFactory                   = $customerAuthFactory;
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
            $composer = $this->customerAuthFactory->create();
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
        $collection = $this->collectionFactory->create();

        $this->addFiltersToCollection($searchCriteria, $collection);
        $this->addSortOrdersToCollection($searchCriteria, $collection);
        $this->addPagingToCollection($searchCriteria, $collection);

        $collection->load();

        return $this->buildSearchResult($searchCriteria, $collection);
    }
    private function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
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

    private function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }

    private function addPagingToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }

    private function buildSearchResult(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $searchResults = $this->composerSearchResultsInterfaceFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
