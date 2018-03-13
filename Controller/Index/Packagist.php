<?php

namespace Eadesigndev\ComposerRepo\Controller\Index;

use Eadesigndev\ComposerRepo\Model\Packages;
use Eadesigndev\ComposerRepo\Model\Customer\CustomerPackages;
use Eadesigndev\ComposerRepo\Model\Customer\CustomerPackagesRepository;
use Eadesigndev\ComposerRepo\Model\ComposerRepoRepository;
use Eadesigndev\ComposerRepo\Model\Customer\CustomerAuth;
use Eadesigndev\ComposerRepo\Model\CustomerAuthFactory;
use Eadesigndev\ComposerRepo\Model\CustomerPackagesFactory;
use Eadesigndev\ComposerRepo\Model\Customer\CustomerAuthRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

/**
 * Class Packagist
 * @package Eadesigndev\ComposerRepo\Controller\Packagist
 */
class Packagist extends Action
{
    /**
     * @var Session
     */
    private $session;
    /**
     * @var Packages
     */
    private $packages;
    /**
     * @var CustomerPackages
     */
    private $customerPackages;
    /**
     * @var CustomerPackagesRepository
     */
    private $customerPackagesRepository;
    /**
     * @var ComposerRepoRepository
     */
    private $composerRepoRepository;
    /**
     * @var CustomerAuth
     */
    private $customerAuth;
    /**
     * @var CustomerAuthFactory
     */
    private $customerAuthFactory;
    /**
     * @var CustomerPackagesFactory
     */
    private $customerPackagesFactory;
    /**
     * @var CustomerAuthRepository
     */
    private $customerAuthRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteria;

    /**
     * Packagist constructor.
     * @param Context $context
     * @param Session $session
     * @param Packages $packages
     * @param CustomerPackages $customerPackages
     * @param CustomerPackagesRepository $customerPackagesRepository
     * @param ComposerRepoRepository $composerRepoRepository
     * @param CustomerAuth $customerAuth
     * @param CustomerAuthFactory $customerAuthFactory
     * @param CustomerPackagesFactory $customerPackagesFactory
     * @param CustomerAuthRepository $customerAuthRepository
     * @param SearchCriteriaBuilder $searchCriteria
     */

    public function __construct(
        Context $context,
        Session $session,
        Packages $packages,
        CustomerPackages $customerPackages,
        CustomerPackagesRepository $customerPackagesRepository,
        ComposerRepoRepository $composerRepoRepository,
        CustomerAuth $customerAuth,
        CustomerAuthFactory $customerAuthFactory,
        CustomerPackagesFactory $customerPackagesFactory,
        CustomerAuthRepository $customerAuthRepository,
        SearchCriteriaBuilder $searchCriteria
    ) {
        parent::__construct($context);
        $this->session = $session;
        $this->packages = $packages;
        $this->customerPackages = $customerPackages;
        $this->customerPackagesRepository = $customerPackagesRepository;
        $this->composerRepoRepository = $composerRepoRepository;
        $this->customerAuth = $customerAuth;
        $this->customerAuthFactory = $customerAuthFactory;
        $this->customerPackagesFactory = $customerPackagesFactory;
        $this->customerAuthRepository = $customerAuthRepository;
        $this->searchCriteria = $searchCriteria;
    }

    public function execute()
    {
        if (!$this->getRequest()->getServer('PHP_AUTH_USER')) {
            $this->unAuthResponse();
            return false;
        }

        $this->getRequest()->getServer('PHP_AUTH_USER');
        $this->getRequest()->getServer('PHP_AUTH_PW');

        $session = $this->session;
        $isLoggedIn = $session->isLoggedIn();
        $customerId = $session->getCustomerId();

        if (!$isLoggedIn) {
            $this->unAuthResponse();
            return false;
        }

        if (!$customerId) {
            $this->unAuthResponse();
            return false;
        }
            $searchCriteriaBuilder = $this->searchCriteria;
            $searchCriteria = $searchCriteriaBuilder->addFilter(
                'customer_id',
                $customerId,
                'eq')->create();
            $customerAuth = $this->customerAuthRepository->getList($searchCriteria);
            $items = $customerAuth->getItems();
            $lastElementPackage = end($items);

        return $isLoggedIn;
    }

    public function unAuthResponse()
    {
        $this->getResponse()
            ->setHttpResponseCode(401)
            ->setHeader('WWW-Authenticate', 'Basic realm="Genmato Composer Repository"', true)
            ->setHeader('HTTP/1.0', '401 Unauthorized')
            ->setBody('Unauthorized Access!');
    }
}