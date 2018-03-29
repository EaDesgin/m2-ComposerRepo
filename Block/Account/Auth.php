<?php

namespace Eadesigndev\ComposerRepo\Block\Account;

use Eadesigndev\ComposerRepo\Model\Customer\CustomerAuthRepository;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Customer\Model\Session;

/**
 * Class Auth
 * @package Eadesigndev\ComposerRepo\Block\Account\Auth
 */
class Auth extends Template
{
    /**
     * @var Session
     */
    private $session;
    /**
     * @var CustomerAuthRepository
     */
    private $customerAuthRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteria;

    /**
     * Auth constructor.
     * @param Template\Context $context
     * @param Session $session
     * @param SearchCriteriaBuilder $searchCriteria
     * @param CustomerAuthRepository $customerAuthRepository
     */
    public function __construct(
        Template\Context $context,
        Session $session,
        SearchCriteriaBuilder $searchCriteria,
        CustomerAuthRepository $customerAuthRepository,
        array $data = []
    ) {
        $this->session = $session;
        $this->searchCriteria = $searchCriteria;
        $this->customerAuthRepository = $customerAuthRepository;

        parent::__construct($context, $data);
    }

    public function customerKey()
    {
        $session = $this->session;
        $isLoggedIn = $session->isLoggedIn();
        $customerId = $session->getCustomerId();

        $searchCriteriaBuilder = $this->searchCriteria;
        $searchCriteria = $searchCriteriaBuilder->addFilter(
            'customer_id',
            10
        )->create();
        $customerKey = $this->customerAuthRepository->getList($searchCriteria);
        $items = $customerKey->getItems();

        return $items;
    }
}