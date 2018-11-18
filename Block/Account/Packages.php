<?php

namespace Eadesigndev\ComposerRepo\Block\Account;

use Eadesigndev\ComposerRepo\Helper\Data;
use Eadesigndev\ComposerRepo\Model\Customer\CustomerPackagesRepository;
use Eadesigndev\ComposerRepo\Model\PackagesRepository;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Customer\Model\SessionFactory;

/**
 * Class Packages
 * @package Eadesigndev\ComposerRepo\Block\Account\Packages
 */
class Packages extends Template
{
    const PACKAGE = 'package';

    /**
     * @var SessionFactory
     */
    private $sessionFactory;
    /**
     * @var CustomerPackagesRepository
     */
    private $customerPackagesRepository;
    /**
     * @var PackagesRepository
     */
    private $packagesRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteria;

    /**
     * @var Data
     */
    private $dataHelper;

    /**
     * Auth constructor.
     * @param Template\Context $context
     * @param SessionFactory $sessionFactory
     * @param SearchCriteriaBuilder $searchCriteria
     * @param CustomerPackagesRepository $customerPackagesRepository
     */
    public function __construct(
        Template\Context $context,
        SessionFactory $sessionFactory,
        SearchCriteriaBuilder $searchCriteria,
        PackagesRepository $packagesRepository,
        CustomerPackagesRepository $customerPackagesRepository,
        Data $dataHelper,
        array $data = []
    ) {
        $this->sessionFactory             = $sessionFactory;
        $this->searchCriteria             = $searchCriteria;
        $this->packagesRepository         = $packagesRepository;
        $this->customerPackagesRepository = $customerPackagesRepository;
        $this->dataHelper                 = $dataHelper;

        parent::__construct($context, $data);
    }

    public function customerPackages()
    {
        $sessionFactory = $this->sessionFactory;
        $session = $sessionFactory->create();
        $customerId = $session->getCustomerId();

        $searchCriteriaBuilder = $this->searchCriteria;
        $searchCriteria = $searchCriteriaBuilder->addFilter(
            'customer_id',
            $customerId
        )->create();
        $customerKey = $this->customerPackagesRepository->getList($searchCriteria);
        $items = $customerKey->getItems();

        $completeItems = [];
        foreach ($items as $item) {
            $completeItems[] = $this->packages($item);
        }

        return $completeItems;
    }

    public function packages($item)
    {
        $package = $this->packagesRepository->getById($item->getId());
        $item->setData(self::PACKAGE, $package);

        return $item;
    }

    public function repoUrl()
    {
        return $this->dataHelper->getConfigUrl();
    }
}
