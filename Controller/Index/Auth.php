<?php

namespace Eadesigndev\ComposerRepo\Controller\Index;

use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session;

/**
 * Class Auth
 * @package Eadesigndev\ComposerRepo\Controller\Auth
 */
class Auth extends AbstractAccount
{
    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var Session
     */
    private $session;

    /**
     * Packages constructor.
     * @param Context $context
     */

    /**
     * Packages constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Session $session
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Session $session
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->session           = $session;
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        if ($this->session->authenticate()) {
            $this->_view->loadLayout();
            if ($block = $this->_view->getLayout()->getBlock('composerrepo_auth')) {
                $block->setRefererUrl($this->_redirect->getRefererUrl());
            }
            $this->_view->getPage()->getConfig()->getTitle()->set(__('Composer authentication keys'));
            $this->_view->renderLayout();
        }
    }
}
