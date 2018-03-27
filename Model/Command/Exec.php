<?php

namespace Eadesigndev\ComposerRepo\Model\Command;

use Eadesigndev\ComposerRepo\Model\Packages;
use Eadesigndev\ComposerRepo\Model\Packages\Version;
use Eadesigndev\ComposerRepo\Model\Packages\VersionRepository;
use Eadesigndev\ComposerRepo\Model\Customer\CustomerPackagesRepository;
use Eadesigndev\ComposerRepo\Model\PackagesRepository;
use Eadesigndev\ComposerRepo\Model\VersionFactory;
use Eadesigndev\ComposerRepo\Model\CustomerPackagesFactory;
use Eadesigndev\ComposerRepo\Helper\Data;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTime;

define('DS', DIRECTORY_SEPARATOR);

class Exec extends AbstractModel
{
    /**
     * @var Data
     */
    private $dataHelper;
    /**
     * @var DateTime
     */
    private $dateTime;
    /**
     * @var UrlInterface
     */
    private $urlInterface;
    /**
     * @var StoreManagerInterface
     */
    private $storeManagerInterface;
    /**
     * @var Packages
     */
    private $packages;
    /**
     * @var Version
     */
    private $versionPackages;
    /**
     * @var VersionRepository
     */
    private $versionRepository;
    /**
     * @var VersionFactory
     */
    private $versionFactory;
    /**
     * @var CustomerPackagesFactory
     */
    private $customerPackagesFactory;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteria;
    /**
     * @var FilterBuilder
     */
    private $filterBuilder;
    /**
     * @var PackagesRepository
     */
    private $packagesRepository;
    /**
     * @var CustomerPackagesRepository
     */
    private $customerPackagesRepository;

    private $store_id = 0;

    /**
     * Exec constructor.
     * @param DateTime $dateTime
     * @param Packages $packages
     * @param Version $versionPackages
     * @param VersionFactory $versionFactory
     * @param CustomerPackagesFactory $customerPackagesFactory
     * @param VersionRepository $versionRepository
     * @param SearchCriteriaBuilder $searchCriteria
     * @param FilterBuilder $filterBuilder
     * @param PackagesRepository $packagesRepository
     * @param CustomerPackagesRepository $customerPackagesRepository
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Packages $packages,
        Version $versionPackages,
        VersionFactory $versionFactory,
        CustomerPackagesFactory $customerPackagesFactory,
        VersionRepository $versionRepository,
        Data $dataHelper,
        DateTime $dateTime,
        UrlInterface $urlInterface,
        StoreManagerInterface $storeManagerInterface,
        SearchCriteriaBuilder $searchCriteria,
        FilterBuilder $filterBuilder,
        PackagesRepository $packagesRepository,
        CustomerPackagesRepository $customerPackagesRepository
    ) {
        parent::__construct(
            $context,
            $registry
        );
        $this->dateTime = $dateTime;
        $this->packages = $packages;
        $this->versionPackages = $versionPackages;
        $this->versionRepository = $versionRepository;
        $this->versionFactory = $versionFactory;
        $this->customerPackagesFactory = $customerPackagesFactory;
        $this->dataHelper = $dataHelper;
        $this->urlInterface = $urlInterface;
        $this->storeManagerInterface = $storeManagerInterface;
        $this->searchCriteria = $searchCriteria;
        $this->filterBuilder = $filterBuilder;
        $this->packagesRepository = $packagesRepository;
        $this->customerPackagesRepository = $customerPackagesRepository;
    }

    /**
     * Run script
     *
     */
    public function run()
    {
        $this->store_id = 1;
        $getConfig = $this->dataHelper;

        if (true) {
            $config = [];
            $config['name'] = $getConfig->getConfigName();
            $config['homepage'] = $getConfig->getConfigUrl();
            $config['output-dir'] = $getConfig->getConfigOutput();
            $config['notify-batch'] = $this->getUrl('eadesign_composerrepo/download/notify');

            $config['repositories'] = $this->getRepositories();
            $config['archive'] = [];
            $config['archive']['directory'] = 'file';
            $config['archive']['format'] = $getConfig->getConfigArchive();
            $config['archive']['prefix-url'] = substr($this->getUrl('eadesign_composerrepo/index/download'), 0, -1);
            $config['archive']['absolute-directory'] = $getConfig->getConfigAbsoluteDir();
            $config['archive']['checksum'] = false;
            $config['archive']['skip-dev'] = false;
            $config['require-all'] = true;

            $satisBin = $getConfig->getConfigSatisBin();
            $satisCfg = $getConfig->getConfigSatisCfg();


            if ($satisBin && $satisCfg) {
                file_put_contents(
                    $satisCfg,
                    $this->json($config)
                );

//                $execute = $satisBin . ' -vvv build ' . $satisCfg;
//                system($execute);

                $packages = json_decode(file_get_contents($getConfig->getConfigOutput() . DS . 'packages.json'), true);
                $currentDateTime = $this->dateTime->gmtDate();
                $includes = $packages['includes'];
                foreach ($includes as $file => $data) {
                    $includeData = json_decode(file_get_contents($getConfig->getConfigOutput() . DS . $file), true);
                    $packages = $this->packages;
                    $includeDataPackages = $includeData['packages'];

                    foreach ($includeDataPackages as $packageName => $packageData) {
                        $packageModel = $packages->getByPackageName($packageName);

                        if (!$packageModel->getId()) {
                            continue;
                        }
                        $this->printLn('Building data for package: ' . $packageModel->getName());

                        $versions = [];
                        $latestVersion = '0.0.0.0';
                        $updatePackageData = false;
                        if (!$getConfig->getConfigDevMaster() && isset($packageData['dev-mas'])) {
                            $this->printLn(' - Removing dev-master from available packages');
                            unset($packageData['dev-master']);
                        }
                        foreach ($packageData as $version => $versionInfo) {
                            unset($versionInfo['source']);
                            if (isset($versionInfo['support']['source'])) {
                                unset($versionInfo['support']['source']);
                            }
                            if (isset($versionInfo['support']['issues'])) {
                                unset($versionInfo['support']['issues']);
                            }

                            $versionNr = $versionInfo['version_normalized'];
                            $filePart = explode(DS, $versionInfo['dist']['url']);

                            $idPackage = $packageModel->getId();
                            $searchCriteriaBuilder = $this->searchCriteria;
                            $searchCriteria = $searchCriteriaBuilder
                                ->addFilter('package_id', $idPackage)
                                ->addFilter('version', $version)
                                ->create();
                            $versionModels = $this->versionRepository->getList($searchCriteria);
                            $items = $versionModels->getItems();
                            $versionModel = end($items);

                            if (!$versionModel) {
                                $versionFactory = $this->versionFactory->create();
                                $versionFactory->setPackageId($idPackage);
                                $versionFactory->setFile(array_pop($filePart));
                                $versionFactory->setVersion($versionNr);

                                $versionModel = $this->versionRepository->save($versionFactory);
                                $updatePackageData = true;
                                $this->printLn(' - Saving new version: ' . $versionNr);
                            }
                            if (strstr($versionModel->getFile(), $versionInfo['dist']['reference']) === false) {
                                $versionFactory = $this->versionFactory->create();
                                $versionFactory->setFile($filePart);
                                $versionFactory->setVersion($versionNr);

                                $this->versionRepository->save();
                                $updatePackageData = true;
                                $this->printLn(' - Saving updated version reference: ' . $versionNr);
                            }

                            $param = [];
                            $param['m'] = str_replace('/', '_', $packageName);
                            $param['h'] = $versionInfo['dist']['reference'];
                            $param['v'] = $versionNr;
                            $versionInfo['dist']['url'] = $this->getUrl('eadesign_composerrepo/index/download/file', $param);

                            $versions[$version] = $versionInfo;
                            if ($versionNr != '9999999-dev' && version_compare($versionNr, $latestVersion, '>')) {
                                $latestVersion = $versionNr;
                            }
                        }

                        if ($packageModel->getVersion() != $latestVersion) {
                            $packageModel->setVersion($latestVersion);
                            $updatePackageData = true;

                            $searchCriteriaBuilder = $this->searchCriteria;
                            $filters = [
                                $this->filterBuilder
                                    ->setField('package_id')
                                    ->setValue($idPackage)
                                    ->setConditionType('eq')
                                    ->create(),
                                $this->filterBuilder
                                    ->setField('status')
                                    ->setValue(1)
                                    ->setConditionType('eq')
                                    ->create(),
                            ];
                            $searchCriteriaBuilder
                                ->addFilters($filters);
                            $searchCriteria = $searchCriteriaBuilder->create();
                            $custPackages = $this->customerPackagesRepository->getList($searchCriteria);
                            $customPackages = $custPackages->getItems();
                            $this->printLn(' - Updating max allowed version for customers');

//                            foreach ($customPackages as $customPackage) {
//                                $this->printLn('   - ' . $customPackage->getCustomerId());
//                                $customerPackagesFactory = $this->customerPackagesFactory->create();
//                                $customerPackagesFactory->setLastAllowedVersion($latestVersion);
//                            }
//                            continue;
//                            $customPackages->save();
                        }
                        if ($updatePackageData) {
                            $packageModel->setPackageJson(json_encode($versions))->save();
                        }
                    }
                }
            }
        }
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

    public function getUrl($route, $params = [])
    {
        $storeId = $this->storeManagerInterface->getStore($this->store_id);

        $url = $storeId->getBaseUrl() . $route;
        foreach ($params as $key => $val) {
            $url .= "/$key/$val";
        }
        return $url;
    }

    public function printLn($msg)
    {
        echo $msg . PHP_EOL;
    }
}