<?php

namespace Eadesigndev\ComposerRepo\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Packages
 * @package @package Eadesigndev\ComposerRepo\Controller\Packages
 */
class Packages extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Packages constructor.
     * @param Context $context
     */

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $this->_view->loadLayout();
        if ($block = $this->_view->getLayout()->getBlock('composerrepo_packages')) {
            $block->setRefererUrl($this->_redirect->getRefererUrl());
        }
        $this->_view->getPage()->getConfig()->getTitle()->set(__('Composer packages'));
        $this->_view->renderLayout();
    }
}
