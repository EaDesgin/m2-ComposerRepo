<?php

namespace Eadesigndev\ComposerRepo\Controller\Adminhtml\Index;

use Eadesigndev\ComposerRepo\Api\ComposerRepositoryInterface;
use Eadesigndev\ComposerRepo\Model\ComposerRepoFactory;
use Magento\Framework\Registry;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\Session;
use Magento\Framework\View\Result\PageFactory;

class Edit extends \Magento\Backend\App\Action
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

    private $composerRepoRepository;

    private $composerRepoFactory;

    private $registry;

    private $session;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ComposerRepositoryInterface $composerRepoRepository,
        ComposerRepoFactory $composerRepoFactory,
        Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->composerRepoRepository  = $composerRepoRepository;
        $this->composerRepoFactory     = $composerRepoFactory;
        $this->registry          = $registry;
        $this->session           = $context->getSession();
        parent::__construct($context);
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
        if ($id) {
            $model = $this->composerRepoRepository->getById($id);
            if (!$model->getEntityId()) {
                $this->messageManager->addErrorMessage(__('This field no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        } else {
            $model = $this->composerRepoFactory->create();
        }
        /** @var Session $data */
        $data = $this->session->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $this->registry->register('composer_data', $model);



        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Magento_Cms::cms_page');
        $resultPage->addBreadcrumb(__('CMS'), __('CMS'));
        $resultPage->addBreadcrumb(__('Edit'), __('Edit'));
        $resultPage->getConfig()->getTitle()->prepend(__('New Magento 2 Composer Package'));

        $dataPersistor = $this->_objectManager->get(\Magento\Framework\App\Request\DataPersistorInterface::class);
        $dataPersistor->clear('cms_page');

        return $resultPage;
    }
}