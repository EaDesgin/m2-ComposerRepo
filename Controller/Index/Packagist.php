<?php

namespace Eadesigndev\ComposerRepo\Controller\Index;

use Eadesigndev\ComposerRepo\Model\Packages;
use Eadesigndev\ComposerRepo\Model\PackagesRepository;
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
use Magento\Framework\Controller\Result\JsonFactory;

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
     * @var JsonFactory
     */
    private $resultJsonFactory;
    /**
     * @var Packages
     */
    private $packages;
    /**
     * @var PackagesRepository
     */
    private $packagesRepository;
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
     * @param JsonFactory $resultJsonFactory
     * @param PackagesRepository $packagesRepository
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
        JsonFactory $resultJsonFactory,
        PackagesRepository $packagesRepository,
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
        $this->resultJsonFactory = $resultJsonFactory;
        $this->packagesRepository = $packagesRepository;
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

//        if (!$isLoggedIn) {
//            $this->unAuthResponse();
//            return false;
//        }
//
//        if (!$customerId) {
//            $this->unAuthResponse();
//            return false;
//        }

        $searchCriteriaBuilder = $this->searchCriteria;
        $searchCriteria = $searchCriteriaBuilder->addFilter(
            'customer_id',
            $customerId,
            'eq')
            ->create();
        $customerAuth = $this->customerAuthRepository->getList($searchCriteria);
        $items = $customerAuth->getItems();
        $lastElementPackage = end($items);


        $searchCriteriaBuilder = $this->searchCriteria;
        $searchCriteria = $searchCriteriaBuilder
            ->addFilter('customer_id', $customerId)
            ->create();
        $customerPackages = $this->customerPackagesRepository->getList($searchCriteria);
        $customerPackage = $customerPackages->getItems();

        foreach ($customerPackage as $item) {
            $customerData = $item;
            $packageId = $customerData->getData('package_id');
        }

        $searchCriteriaBuilder = $this->searchCriteria;
        $searchCriteria = $searchCriteriaBuilder->create();
        $packages = $this->packagesRepository->getList($searchCriteria);
        $items = $packages->getItems();

        foreach ($items as $item) {
            $packageData = $item;
            $packageJson = $packageData->getData('package_json');
            $name = $packageData->getData('name');
            $decodeJson = json_decode($packageJson);

        }
        if ($customerId && $packageId) {
            $responseData = [];
            $responseData = [
                'notify-batch' => 'http://ean.eadesigndevm2.ro/eadesign_composerrepo/download/notify/',
                'cached' => false,
                'packages' => [
                    $name => $decodeJson,
                ]
            ];

            $res = json_encode($responseData, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            $resultJson = $this->resultJsonFactory->create();
            $response = $resultJson->setJsonData($res);

            return $response;
        }
    }

    public function unAuthResponse()
    {
        $this->getResponse()
            ->setHttpResponseCode(401)
            ->setHeader('WWW-Authenticate', 'Basic realm="Eadesign Composer Repository"', true)
            ->setHeader('HTTP/1.0', '401 Unauthorized')
            ->setBody('Unauthorized Access!');
    }
}