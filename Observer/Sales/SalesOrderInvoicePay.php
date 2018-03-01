<?php

namespace Eadesigndev\ComposerRepo\Observer\Sales;

use Eadesigndev\ComposerRepo\Model\Packages;
use Eadesigndev\ComposerRepo\Model\Customer\CustomerPackages;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Invoice;
use Psr\Log\LoggerInterface;


/**
 * Class SalesOrderInvoicePay
 * @package Eadesigndev\ComposerRepo\Observer\Sales
 */
class SalesOrderInvoicePay implements ObserverInterface
{
    const SCOPE_STORE = 'eadesign_composerrepo/configuration/update_period';
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
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * SalesOrderInvoicePay constructor.
     * @param LoggerInterface $logger
     * @param Packages $packages
     * @param CustomerPackages $customerPackages
     */

    public function __construct(
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig,
        Packages $packages,
        CustomerPackages $customerPackages
    )
    {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->packages = $packages;
        $this->customerPackages = $customerPackages;
    }

    /**
     * Execute observer
     * @param Observer $observer
     * @return void
     */
    public function execute(
        Observer $observer
    )
    {
        $event = $observer->getEvent();
        /** @var Invoice $invoice */
        $invoice = $event->getInvoice();
        /** @var Order $order */
        $order = $invoice->getOrder();
        $customerId = $order->getCustomerId();

        $packageModel = $this->packages;
        $installPackages = [];
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

                if ($period = $this->_scopeConfig->getValue(\Magento\Store\Model\ScopeInterface::SCOPE_STORE)) ;
                {
                    $endDate = new DateTime();
                    $endDate->add(new DateInterval('P' . intval($period) . 'M'));

                    $customerPackage->setLastAllowedDate($endDate->format('Y-m-d H:i:s'));
                }
                try {
                    $customerPackage->save();
                } catch (\Exception $e) {
                    $this->logger->info($e->getMessage());
                }
            }
        }
        if (!count($installPackages)) {
            return;
        }
    }
}