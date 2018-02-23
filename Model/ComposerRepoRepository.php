<?php

namespace Eadesigndev\ComposerRepo\Model;

use Eadesigndev\ComposerRepo\Api\Data\ComposerInterface;
use Eadesigndev\ComposerRepo\Api\ComposerRepositoryInterface;
use Eadesigndev\ComposerRepo\Model\ResourceModel\ComposerRepo as ComposerRepoResourceModel;
use Magento\Framework\Exception\LocalizedException as Exception;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class ComposerRepoRepository
 * @package Eadesigndev\ComposerRepo\Model
 */
class ComposerRepoRepository implements ComposerRepositoryInterface
{
    /**
     * @var array
     */
    private $instances = [];

    /**
     * @var ComposerRepoResourceModel
     */
    private $resource;

    /**
     * @var ComposerInterface
     */
    private $composer;

    /**
     * @var ComposerRepoFactory
     */
    private $composerRepoFactory;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * ComposerRepoRepository constructor.
     * @param ComposerRepoResourceModel $resource
     * @param ComposerInterface $composer
     * @param ComposerRepoFactory $composerRepoFactory
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        ComposerRepoResourceModel $resource,
        ComposerInterface $composer,
        ComposerRepoFactory $composerRepoFactory,
        ManagerInterface $messageManager
    ) {
        $this->resource = $resource;
        $this->composer = $composer;
        $this->composerRepoFactory = $composerRepoFactory;
        $this->messageManager = $messageManager;
    }

    /**
     * @param ComposerInterface $composer
     * @return ComposerInterface
     * @throws \Exception
     */
    public function save(ComposerInterface $composer)
    {
        try {
            $this->resource->save($composer);
        } catch (Exception $e) {
            $this->messageManager
                ->addExceptionMessage(
                    $e,
                    'There was a error while saving the package ' . $e->getMessage()
                );
        }

        return $composer;
    }

    /**
     * @param $composerId
     * @return array
     */
    public function getById($composerId)
    {
        if (!isset($this->instances[$composerId])) {
            $composer = $this->composerRepoFactory->create();
            $this->resource->load($composer, $composerId);
            $this->instances[$composerId] = $composer;
        }
        return $this->instances[$composerId];
    }

    /**
     * @param ComposerInterface $composer
     * @return bool
     * @throws \Exception
     */
    public function delete(ComposerInterface $composer)
    {
        $id = $composer->getEntityId();
        try {
            unset($this->instances[$id]);
            $this->resource->delete($composer);
        } catch (Exception $e) {
            $this->messageManager
                ->addExceptionMessage($e, 'There was a error while deleting the package');
        }
        unset($this->instances[$id]);
        return true;
    }

    /**
     * @param $composerId
     * @return bool
     * @throws \Exception
     */
    public function deleteById($composerId)
    {
        $composer = $this->getById($composerId);
        return $this->delete($composer);
    }

    /**
     * @param ComposerInterface $composer
     * @return bool
     * @throws \Exception
     */
    public function saveAndDelete(ComposerInterface $composer)
    {
        $composer->setData(Data::ACTION, Data::DELETE);
        $this->save($composer);
        return true;
    }

    /**
     * @param $composerId
     * @return bool
     * @throws \Exception
     */
    public function saveAndDeleteById($composerId)
    {
        $composer = $this->getById($composerId);
        return $this->saveAndDelete($composer);
    }
}