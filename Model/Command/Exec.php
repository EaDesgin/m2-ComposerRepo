<?php

namespace Eadesigndev\ComposerRepo\Model\Command;

use Eadesigndev\ComposerRepo\Model\Packages;
use Eadesigndev\ComposerRepo\Model\PackagesRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;

class Exec extends AbstractModel
{
    /**
     * @var Packages
     */
    private $packages;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteria;
    /**
     * @var PackagesRepository
     */
    private $packagesRepository;

    /**
     * Exec constructor.
     * @param Packages $packages
     * @param SearchCriteriaBuilder $searchCriteria
     * @param PackagesRepository $packagesRepository
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Packages $packages,
        SearchCriteriaBuilder $searchCriteria,
        PackagesRepository $packagesRepository
    )
    {
        parent::__construct(
            $context,
            $registry
        );
        $this->packages = $packages;
        $this->searchCriteria = $searchCriteria;
        $this->packagesRepository = $packagesRepository;
    }

    protected function json($array)
    {
        return json_encode($array, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    public function getRepositories()
    {
        $result = [];
        $searchCriteriaBuilder = $this->searchCriteria;
        $searchCriteria = $searchCriteriaBuilder->addFilter(
            'status',
            [
                'neq' => 0
            ]
        )->create();
        $collection = $this->packagesRepository->getList($searchCriteria);
        $items = $collection->getItems();

        foreach ($items as $package) {
            $item = json_decode($package->getRepositoryOptions(), true);
            if (!isset($item['type'])) {
                $item['type'] = 'vcs';
            }
            $item['url'] = $package->getRepositoryUrl();
            $result[] = $item;
        }
        return $result;
    }
}