<?php
/**
 * Copyright © 2018 EaDesign by Eco Active S.R.L. All rights reserved.
 * See LICENSE for license details.
 */

/**
 * This code sequence will be added if you want to implement the method to filter by version.
 * $versionRequest   = $request->getParam('v');
 * $packageFilter[] = $this->filterBuilder
 * ->setField('version')
 * ->setValue($version)
 * ->setConditionType('eq')
 * ->create();
 */

namespace Eadesigndev\ComposerRepo\Controller\Index;

use Eadesigndev\ComposerRepo\Helper\Data as DataHelper;
use Eadesigndev\ComposerRepo\Model\PackagesRepository;
use Eadesigndev\ComposerRepo\Model\Customer\CustomerAuth;
use Eadesigndev\ComposerRepo\Model\Packages\VersionRepository;
use Eadesigndev\ComposerRepo\Model\Customer\CustomerAuthRepository;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Api\FilterBuilder;

/**
 * Class Download
 * @package Eadesigndev\ComposerRepo\Controller\Download
 */
class Download extends AbstractAccount
{
    private $dataHelper;

    private $resultJsonFactory;

    private $fileFactory;

    private $packagesRepository;

    private $versionRepository;

    private $customerAuth;

    private $customerAuthRepository;

    private $searchCriteria;

    private $filterBuilder;

    /**
     * Download Controller constructor.
     * @param Context $context
     * @param DataHelper $dataHelper
     * @param FileFactory $fileFactory
     * @param JsonFactory $resultJsonFactory
     * @param PackagesRepository $packagesRepository
     * @param CustomerAuth $customerAuth
     * @param CustomerAuthRepository $customerAuthRepository
     * @param SearchCriteriaBuilder $searchCriteria
     * @param VersionRepository $versionRepository
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        Context $context,
        DataHelper $dataHelper,
        JsonFactory $resultJsonFactory,
        FileFactory $fileFactory,
        PackagesRepository $packagesRepository,
        CustomerAuth $customerAuth,
        CustomerAuthRepository $customerAuthRepository,
        SearchCriteriaBuilder $searchCriteria,
        VersionRepository $versionRepository,
        FilterBuilder $filterBuilder
    ) {
        $this->dataHelper             = $dataHelper;
        $this->fileFactory            = $fileFactory;
        $this->customerAuth           = $customerAuth;
        $this->searchCriteria         = $searchCriteria;
        $this->filterBuilder          = $filterBuilder;
        $this->versionRepository      = $versionRepository;
        $this->resultJsonFactory      = $resultJsonFactory;
        $this->packagesRepository     = $packagesRepository;
        $this->customerAuthRepository = $customerAuthRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        /**
         * Defined variables to authentication keys, secret keys, paramNameRequest etc.
         */
        $request = $this->getRequest();
        $ds      = DIRECTORY_SEPARATOR;
        $baseDir = DirectoryList::VAR_DIR;
        $fileFactory      = $this->fileFactory;
        $contentType      = 'application/octet-stream';
        $configHelper     = $this->dataHelper;
        $publicKey        = $request->getServer('PHP_AUTH_USER');
        $privateKey       = $request->getServer('PHP_AUTH_PW');
        $paramNameRequest = $request->getParam('m');
        $packagePathDir   = $configHelper->getConfigAbsoluteDir();

        if (!$publicKey) {
            $this->unAuthResponse();
            return false;
        }

        $searchCriteriaBuilder = $this->searchCriteria;
        $searchCriteria = $searchCriteriaBuilder->addFilter(
            'auth_key',
            $publicKey,
            'eq'
        )->create();
        $authenticationList = $this->customerAuthRepository->getList($searchCriteria);
        $items = $authenticationList->getItems();

        if (empty($items)) {
            $this->unAuthResponse();
            return false;
        }

        $item = end($items);

        if ($item->getData('auth_secret') !== $privateKey) {
            $this->unAuthResponse();
            return false;
        }

        $packageName = str_replace('_', '/', $paramNameRequest);

        $searchCriteriaBuilder = $this->searchCriteria;
        $searchCriteria = $searchCriteriaBuilder->addFilter(
            'name',
            $packageName,
            'eq'
        )->create();
        $packagesFiles = $this->packagesRepository->getList($searchCriteria);
        $packagesItems = $packagesFiles->getItems();

        foreach ($packagesItems as $item) {
            $entityId = $item->getData('entity_id');
        }

        $packageFilter[] = $this->filterBuilder
            ->setField('package_id')
            ->setValue($entityId)
            ->setConditionType('eq')
            ->create();

        $searchCriteriaBuilder = $this->searchCriteria;
        $searchCriteria = $searchCriteriaBuilder
            ->addFilters($packageFilter)
            ->create();
        $versionFile = $this->versionRepository->getList($searchCriteria);
        $itemsPackageVersion = $versionFile->getItems();
        $lastItem = end($itemsPackageVersion);

        $versionPackageData = $lastItem;
        $file = $versionPackageData->getData('file');

        $correctPathFile = $packagePathDir . $ds . $packageName . $ds . $file;

        $fileName = $file;
        $content = file_get_contents($correctPathFile, true);
        $fileDownload = $fileFactory->create($fileName, $content, $baseDir, $contentType);

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
