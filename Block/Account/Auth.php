<?php

namespace Eadesigndev\ComposerRepo\Block\Account;

use Eadesigndev\ComposerRepo\Model\Customer\CustomerAuthRepository;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Customer\Model\SessionFactory;

/**
 * Class Auth
 * @package Eadesigndev\ComposerRepo\Block\Account\Auth
 */
class Auth extends Template
{
    /**
     * @var SessionFactory
     */
    private $sessionFactory;
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
     * @param SessionFactory $sessionFactory
     * @param SearchCriteriaBuilder $searchCriteria
     * @param CustomerAuthRepository $customerAuthRepository
     */
    public function __construct(
        Template\Context $context,
        SessionFactory $sessionFactory,
        SearchCriteriaBuilder $searchCriteria,
        CustomerAuthRepository $customerAuthRepository,
        array $data = []
    ) {
        $this->sessionFactory = $sessionFactory;
        $this->searchCriteria = $searchCriteria;
        $this->customerAuthRepository = $customerAuthRepository;

        parent::__construct($context, $data);
    }

    public function customerKey()
    {
        $sessionFactory = $this->sessionFactory;
        $session = $sessionFactory->create();
        $customerId = $session->getCustomerId();

        $searchCriteriaBuilder = $this->searchCriteria;
        $searchCriteria = $searchCriteriaBuilder->addFilter(
            'customer_id',
            $customerId
        )->create();
        $customerKey = $this->customerAuthRepository->getList($searchCriteria);
        $items = $customerKey->getItems();

        return $items;
    }
}
