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
use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Api\FilterBuilder;

/**
 * Class Packagist
 * @package Eadesigndev\ComposerRepo\Controller\Packagist
 */
class Packagist extends AbstractAccount
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
     * @var FilterBuilder
     */
    private $filterBuilder;

    private $jsonResultFactory;


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
     * @param FilterBuilder $filterBuilder
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
        SearchCriteriaBuilder $searchCriteria,
        FilterBuilder $filterBuilder,
        \Magento\Framework\Controller\Result\JsonFactory $jsonResultFactory
    ) {
        parent::__construct($context);
        $this->session                    = $session;
        $this->packages                   = $packages;
        $this->resultJsonFactory          = $resultJsonFactory;
        $this->packagesRepository         = $packagesRepository;
        $this->customerPackages           = $customerPackages;
        $this->customerPackagesRepository = $customerPackagesRepository;
        $this->composerRepoRepository     = $composerRepoRepository;
        $this->customerAuth               = $customerAuth;
        $this->customerAuthFactory        = $customerAuthFactory;
        $this->customerPackagesFactory    = $customerPackagesFactory;
        $this->customerAuthRepository     = $customerAuthRepository;
        $this->searchCriteria             = $searchCriteria;
        $this->jsonResultFactory          = $jsonResultFactory;
        $this->filterBuilder              = $filterBuilder;
    }

    public function execute()
    {
        $authKey = $this->getRequest()->getServer('PHP_AUTH_USER');

        if (!$authKey) {
            $this->unAuthResponse();
            return false;
        }

        $searchCriteriaBuilder = $this->searchCriteria;
        $authCriteria = $searchCriteriaBuilder
            ->addFilter('auth_key', $authKey, 'eq')
            ->create();

        $authList = $this->customerAuthRepository->getList($authCriteria);
        $items = $authList->getItems();
        if (empty($items)) {
            $this->unAuthResponse();
            return false;
        }

        $item = end($items);

        $authSecret = $this->getRequest()->getServer('PHP_AUTH_PW');

        if ($item->getData('auth_secret') !== $authSecret) {
            $this->unAuthResponse();
            return false;
        }

        $customerId = $item->getData('customer_id');

        $searchCriteriaBuilder = $this->searchCriteria;
        $searchCriteria = $searchCriteriaBuilder
            ->addFilter('customer_id', $customerId)
            ->create();
        $customerPackages = $this->customerPackagesRepository->getList($searchCriteria);
        $customerPackage  = $customerPackages->getItems();

        foreach ($customerPackage as $customerData) {
            $packageId[] = $customerData->getData('package_id');
        }

        $packageFilter[] = $this->filterBuilder
            ->setField('entity_id')
            ->setValue($packageId)
            ->setConditionType('in')
            ->create();

        $searchCriteriaBuilder = $this->searchCriteria;
        $searchCriteria = $searchCriteriaBuilder
            ->addFilters($packageFilter)
            ->create();
        $packages = $this->packagesRepository->getList($searchCriteria);
        $items = $packages->getItems();

        foreach ($items as $packageId) {
            $packageJson = $packageId->getData('package_json');
            $name = $packageId->getData('name');
            $decodeJson[$name] = json_decode($packageJson);
        }

        $responseData = [
            'notify-batch' => 'https://www.eadesign.ro/eadesign_composerrepo/index/packagist/',
            'cached' => false,
            'packages' => $decodeJson
        ];

        if ($customerId && $packageId) {
            $res = json_encode($responseData, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            $resultJson = $this->resultJsonFactory->create();
            $response = $resultJson->setJsonData($res);
        }
        return $response;
    }

    public function unAuthResponse()
    {
        $this->getResponse()
            ->setHttpResponseCode(401)
            ->setHeader('WWW-Authenticate', 'Basic realm="Eadesign Composer Repository"', true)
            ->setBody('Unauthorized Access!');
    }
}
