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
use Eadesigndev\ComposerRepo\Helper\Data;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Customer\Model\Session;
use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Response\Http\FileFactory;
use Eadesigndev\ComposerRepo\Model\Packages\VersionRepository;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\App\Filesystem\DirectoryList;


/**
 * Class Download
 * @package Eadesigndev\ComposerRepo\Controller\Download
 */
class Download extends AbstractAccount
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var Data
     */
    private $dataHelper;
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;
    /**
     * @var FileFactory
     */
    private $fileFactory;
    /**
     * @var RawFactory
     */
    private $rawFactory;
    /**
     * @var Packages
     */
    private $packages;
    /**
     * @var PackagesRepository
     */
    private $packagesRepository;
    /**
     * @var VersionRepository
     */
    private $versionRepository;
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
     * @param Data $dataHelper
     * @param FileFactory $fileFactory
     * @param RawFactory $rawFactory
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
     * @param VersionRepository $versionRepository
     */

    public function __construct(
        Context $context,
        Session $session,
        Packages $packages,
        Data $dataHelper,
        RawFactory $rawFactory,
        JsonFactory $resultJsonFactory,
        FileFactory $fileFactory,
        PackagesRepository $packagesRepository,
        CustomerPackages $customerPackages,
        CustomerPackagesRepository $customerPackagesRepository,
        ComposerRepoRepository $composerRepoRepository,
        CustomerAuth $customerAuth,
        CustomerAuthFactory $customerAuthFactory,
        CustomerPackagesFactory $customerPackagesFactory,
        CustomerAuthRepository $customerAuthRepository,
        SearchCriteriaBuilder $searchCriteria,
        VersionRepository $versionRepository
    ) {
        parent::__construct($context);
        $this->session = $session;
        $this->packages = $packages;
        $this->dataHelper = $dataHelper;
        $this->fileFactory = $fileFactory;
        $this->rawFactory = $rawFactory;
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
        $this->versionRepository = $versionRepository;
    }

    public function execute()
    {
        $authKey = $this->getRequest()->getServer('PHP_AUTH_USER');

        if (!$authKey) {
            $this->unAuthResponse();
            return false;
        }

        $authCriteriaBuilder = $this->searchCriteria;
        $authCriteria = $authCriteriaBuilder->addFilter(
            'auth_key',
            $authKey,
            'eq'
        )->create();

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

        $packageName = str_replace('_', '/', $this->getRequest()->getParam('m'));
        $version = $this->getRequest()->getParam('v');

        $searchCriteriaBuilder = $this->searchCriteria;
        $searchCriteria = $searchCriteriaBuilder->addFilter(
            'version',
            $version,
            'eq'
        )->create();
        $versionFile = $this->versionRepository->getList($searchCriteria);
        $items = $versionFile->getItems();

        $item = end($items);

        $fileData = $item;
        $file = $fileData->getData('file');

        $packageDir = $this->dataHelper->getConfigAbsoluteDir();
        $correctPath = $packageDir . DIRECTORY_SEPARATOR . $packageName . DIRECTORY_SEPARATOR . $file;

        $fileName = $file;
        $content = file_get_contents($correctPath, true);
        $fileDownload = $this->fileFactory->create(
            $fileName,
            $content,
            DirectoryList::VAR_DIR,
            'application/octet-stream'
        );

        return $fileDownload;
    }

    private function unAuthResponse()
    {
        $this->getResponse()
            ->setHttpResponseCode(401)
            ->setHeader('WWW-Authenticate', 'Basic realm="Eadesign Composer Repository"', true)
            ->setBody('Unauthorized Access!');
    }
}
