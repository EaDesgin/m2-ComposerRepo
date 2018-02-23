<?php

namespace Eadesigndev\ComposerRepo\Controller\Adminhtml\Index;

use Eadesigndev\ComposerRepo\Model\ComposerRepo;
use Eadesigndev\ComposerRepo\Model\ComposerRepoRepository;
use Eadesigndev\ComposerRepo\Model\ComposerRepoFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action
{
//    private $dataProcessor;

    private $dataPersistor;

    private $composerRepoRepository;

    private $composerRepoFactory;

    public function __construct(
        Context $context,
//        PostDataProcessor $dataProcessor,
        DataPersistorInterface $dataPersistor,
        ComposerRepoRepository $composerRepoRepository,
        ComposerRepoFactory $composerRepoFactory
    ) {
//        $this->dataProcessor = $dataProcessor;
        $this->dataPersistor = $dataPersistor;
        $this->composerRepoRepository = $composerRepoRepository;
        $this->composerRepoFactory = $composerRepoFactory;

        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $id = $this->getRequest()->getParam('entity_id');
            if ($id) {
                /** @var ComposerRepo $model */
                $model = $this->composerRepoRepository->getById($id);
            } else {
                unset($data['entity_id']);
                /** @var ComposerRepo $model */
                $model = $this->composerRepoFactory->create();
            }

            $model->setData($data);

            $model->setData('update_time', time());

            try {
                $this->composerRepoRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the field.'));
                $this->dataPersistor->clear('composer_data');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect
                        ->setPath('*/*/edit', ['entity_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage(
                    $e,
                    __('Something went wrong while saving the field.')
                );
            }

            $this->dataPersistor->set('composer_data', $data);
            return $resultRedirect
                ->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Check the permission to run it
     *
     * @return boolean
     */
    //@codingStandardsIgnoreLine
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(Index::ADMIN_RESOURCE);
    }
}