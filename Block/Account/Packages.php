<?php

namespace Eadesigndev\ComposerRepo\Block\Account;

use Eadesigndev\ComposerRepo\Model\Customer\CustomerPackagesRepository;
use Eadesigndev\ComposerRepo\Model\PackagesRepository;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Customer\Model\Session;

/**
 * Class Packages
 * @package Eadesigndev\ComposerRepo\Block\Account\Packages
 */
class Packages extends Template
{
    /**
     * @var Session
     */
    private $session;
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
     * Auth constructor.
     * @param Template\Context $context
     * @param Session $session
     * @param SearchCriteriaBuilder $searchCriteria
     * @param CustomerPackagesRepository $customerPackagesRepository
     */
    public function __construct(
        Template\Context $context,
        Session $session,
        SearchCriteriaBuilder $searchCriteria,
        PackagesRepository $packagesRepository,
        CustomerPackagesRepository $customerPackagesRepository,
        array $data = []
    ) {
        $this->session = $session;
        $this->searchCriteria = $searchCriteria;
        $this->packagesRepository = $packagesRepository;
        $this->customerPackagesRepository = $customerPackagesRepository;

        parent::__construct($context, $data);
    }

    public function customerPackages()
    {
        $session = $this->session;
        $isLoggedIn = $session->isLoggedIn();
        $customerId = $session->getCustomerId();

        $searchCriteriaBuilder = $this->searchCriteria;
        $searchCriteria = $searchCriteriaBuilder->addFilter(
            'customer_id',
            10
        )->create();
        $customerKey = $this->customerPackagesRepository->getList($searchCriteria);
        $items = $customerKey->getItems();

        return $items;
    }

    public function packages()
    {
        $searchCriteriaBuilder = $this->searchCriteria;
        $searchCriteria = $searchCriteriaBuilder->addFilter(
            'entity_id',
            6
        )->create();
        $packages = $this->packagesRepository->getList($searchCriteria);
        $items = $packages->getItems();

        return $items;
    }
}