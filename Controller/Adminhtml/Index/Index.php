<?php

namespace Eadesigndev\ComposerRepo\Controller\Adminhtml\Index;

use Eadesigndev\ComposerRepo\Model\Command\Exec;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Eadesigndev_ComposerRepo::composerrepo';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var Exec
     */
    private $exec;
    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Exec $exec
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Exec $exec
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->exec = $exec;
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE);
        $resultPage->addBreadcrumb(__('CMS'), __('CMS'));
        $resultPage->addBreadcrumb(__('Packages'), __('Packages'));
        $resultPage->getConfig()->getTitle()->prepend(__('Composer Packages'));

        /** Start test debug */
        $this->exec->run();
        /** Stop test debug */
        $dataPersistor = $this->_objectManager->get(\Magento\Framework\App\Request\DataPersistorInterface::class);
        $dataPersistor->clear('cms_page');

        return $resultPage;
    }

    /**
     * @return bool
     */
    public function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }
}