<?php

namespace Eadesigndev\ComposerRepo\Observer\Sales;

use Eadesigndev\ComposerRepo\Model\ComposerRepo;
use Eadesigndev\ComposerRepo\Model\Packages;
use Eadesigndev\ComposerRepo\Model\ComposerRepoRepository;
use Eadesigndev\ComposerRepo\Model\Customer\CustomerPackages;
use Eadesigndev\ComposerRepo\Model\Customer\CustomerPackagesRepository;
use Eadesigndev\ComposerRepo\Model\Customer\CustomerAuth;
use Eadesigndev\ComposerRepo\Model\Customer\CustomerAuthRepository;
use Eadesigndev\ComposerRepo\Model\CustomerAuthFactory;
use Eadesigndev\ComposerRepo\Model\CustomerPackagesFactory;
use Eadesigndev\ComposerRepo\Helper\Data;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Invoice\Item;
use Psr\Log\LoggerInterface;

/**
 * Class SalesOrderInvoicePay
 * @package Eadesigndev\ComposerRepo\Observer\Sales
 * @SuppressWarnings(coupling)
 */
class SalesOrderInvoicePay implements ObserverInterface
{
    /**
     * @var Packages
     */
    private $packages;
    /**
     * @var CustomerPackagesRepository
     */
    private $customerPackagesRepository;
    /**
     * @var CustomerPackagesFactory
     */
    private $customerPackagesFactory;
    /**
     * @var CustomerAuth
     */
    private $customerAuth;
    /**
     * @var CustomerAuthFactory
     */
    private $customerAuthFactory;
    /**
     * @var CustomerAuthRepository
     */
    private $customerAuthRepository;
    /**
     * @var CustomerPackages
     */
    private $customerPackages;

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Data
     */
    private $dataHelper;
    /**
     * @var ComposerRepoRepository
     */
    private $composerRepoRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteria;

    /**
     * SalesOrderInvoicePay constructor.
     * @param Packages $packages
     * @param CustomerAuth $customerAuth
     * @param CustomerAuthFactory $customerAuthFactory
     * @param CustomerAuthRepository $customerAuthRepository
     * @param Data $dataHelper
     * @param LoggerInterface $logger
     * @param SearchCriteriaBuilder $searchCriteria
     * @param CustomerPackages $customerPackages
     * @param CustomerPackagesRepository $customerPackagesRepository
     * @param CustomerPackagesFactory $customerPackagesFactory
     * @param ComposerRepoRepository $composerRepoRepository
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Packages $packages,
        Data $dataHelper,
        CustomerAuth $customerAuth,
        CustomerAuthFactory $customerAuthFactory,
        CustomerAuthRepository $customerAuthRepository,
        LoggerInterface $logger,
        SearchCriteriaBuilder $searchCriteria,
        CustomerPackages $customerPackages,
        CustomerPackagesRepository $customerPackagesRepository,
        CustomerPackagesFactory $customerPackagesFactory,
        ComposerRepoRepository $composerRepoRepository
    ) {
        $this->logger = $logger;
        $this->dataHelper = $dataHelper;
        $this->packages = $packages;
        $this->customerAuth = $customerAuth;
        $this->customerAuthRepository = $customerAuthRepository;
        $this->customerAuthFactory = $customerAuthFactory;
        $this->customerPackages = $customerPackages;
        $this->searchCriteria = $searchCriteria;
        $this->customerPackagesRepository = $customerPackagesRepository;
        $this->customerPackagesFactory = $customerPackagesFactory;
        $this->composerRepoRepository = $composerRepoRepository;
    }

    /**
     * Execute observer
     * @param Observer $observer
     * @return void
     */
    public function execute(
        Observer $observer
    ) {
        $event = $observer->getEvent();
        /** @var Invoice $invoice */
        $invoice = $event->getInvoice();
        /** @var Order $order */
        $order = $invoice->getOrder();
        $customerId = $order->getCustomerId();

        $searchCriteriaBuilder = $this->searchCriteria;
        $itemsCollection = $invoice->getItemsCollection();
        /** @var Item $item */
        foreach ($itemsCollection as $item) {
            $searchCriteria = $searchCriteriaBuilder->addFilter(
                'product_id',
                $item->getProductId(),
                'eq'
            )->create();
            $package = $this->composerRepoRepository->getList($searchCriteria);
            $items = $package->getItems();
            $lastElementPackage = end($items);

            if ($lastElementPackage instanceof ComposerRepo) {
                $customerPackage = $this->customerPackagesFactory->create();
                $customerPackage->setStatus(1);
                $customerPackage->setCustomerId($customerId);
                $customerPackage->setOrderId($order->getIncrementId());
                $customerPackage->setPackageId($lastElementPackage->getId());
                $customerPackage->setLastAllowedVersion($lastElementPackage->getVersion());

                $period = $this->dataHelper->period();
                if ($period) {
                    $endDate = new \DateTime();
                    $endDate->add(new \DateInterval('P' . intval($period) . 'M'));

                    $customerPackage->setLastAllowedDate($endDate->format('Y-m-d H:i:s'));
                }
                try {
                    $this->customerPackagesRepository->save($customerPackage);
                } catch (\Exception $e) {
                    $this->logger->info($e->getMessage());
                }
            }
        }

        $authKey = $this->dataHelper->generateUniqueAuthKey();
        $secretAuthKey = $this->dataHelper->generateSecretAuthKey();

        $customerKey = $this->customerAuthFactory->create();
        $customerKey->setStatus(1);
        $customerKey->setDefault(1);
        $customerKey->setCustomerId($customerId);
        $customerKey->setDescription('Auto generated key');
        $customerKey->setAuthKey($authKey);
        $customerKey->setAuthSecret($secretAuthKey);

        $this->customerAuthRepository->save($customerKey);
    }
}
