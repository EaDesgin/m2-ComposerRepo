<?php

namespace Eadesigndev\ComposerRepo\Observer\Sales;

use Eadesigndev\ComposerRepo\Model\Packages;
use Eadesigndev\ComposerRepo\Model\Customer\CustomerPackages;
use Eadesigndev\ComposerRepo\Model\ComposerRepoRepository;
use Eadesigndev\ComposerRepo\Model\Customer\CustomerAuth;
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
 */
class SalesOrderInvoicePay implements ObserverInterface
{
    /**
     * @var CustomerAuth
     */
    private $auth;
    /**
     * @var CustomerPackages
     */
    private $customerPackages;
    /**
     * @var Packages
     */
    private $packages;
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
     * @param Data $dataHelper
     * @param CustomerAuth $auth
     * @param LoggerInterface $logger
     * @param SearchCriteriaBuilder $searchCriteria
     * @param CustomerPackages $customerPackages
     * @param ComposerRepoRepository $composerRepoRepository
     */

    public function __construct(
        Packages $packages,
        Data $dataHelper,
        CustomerAuth $auth,
        LoggerInterface $logger,
        SearchCriteriaBuilder $searchCriteria,
        CustomerPackages $customerPackages,
        ComposerRepoRepository $composerRepoRepository
    ) {
        $this->logger                 = $logger;
        $this->dataHelper             = $dataHelper;
        $this->packages               = $packages;
        $this->auth                   = $auth;
        $this->customerPackages       = $customerPackages;
        $this->searchCriteria         = $searchCriteria;
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

        $packageModel = $this->packages;
        $installPackages = [];

        $searchCriteriaBuilder = $this->searchCriteria;
        $searchCriteria = $searchCriteriaBuilder->addFilter('product_id',28, 'eq')->create();
        $items = $this->composerRepoRepository->getList($searchCriteria);
        $items = $items->getItems();


        /** @var Item $item */
        foreach ($invoice->getItemsCollection() as $item) {
            $package = $packageModel->load($item->getProductId(), 'product_id');


            if ($package->getId()) {
                $installPackages[] = $package;

                $customerPackage = $this->customerPackages
                    ->setStatus(1)
                    ->setCustomerId($customerId)
                    ->setOrderId($order->getId())
                    ->setPackageId($package->getId())
                    ->setLastAllowedVersion($package->getVersion());

//                if ($period = $this->dataHelper->hasConfig(\Magento\Store\Model\ScopeInterface::SCOPE_STORE)) ;
//                {
//                    $endDate = new DateTime();
//                    $endDate->add(new DateInterval('P' . intval($period) . 'M'));
//
//                    $customerPackage->setLastAllowedDate($endDate->format('Y-m-d H:i:s'));
//                }
//                try {
//                    $customerPackage->save();
//                } catch (\Exception $e) {
//                    $this->logger->info($e->getMessage());
//                }
            }
        }
        if (!count($installPackages)) {
            return;
        }
    }
}