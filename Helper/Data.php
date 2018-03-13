<?php

namespace Eadesigndev\ComposerRepo\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Math\Random;

/**
 * Handles the config and other settings
 *
 * Class Data
 * @package Eadesigndev\ComposerRepo\Helper
 */
class Data extends AbstractHelper
{
    const STORE_CONFIG = 'eadesign_composerrepo/composerrepo_config/update_period';

    /**
     * @var ScopeConfigInterface
     */
    public $config;

    /**
     * @var Random
     */
    private $randomString;

    /**
     * Data constructor.
     * @param Context $context
     */
    public function __construct(
        Context $context,
        Random $randomString
    ) {
        $this->config = $context->getScopeConfig();
        parent::__construct($context);
        $this->randomString = $randomString;
    }

    /**
     * @param string $configPath
     * @return bool
     */
    public function getConfig($configPath)
    {
        return $this->config->getValue(
            $configPath,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function period($storeConfig = self::STORE_CONFIG)
    {
        return $this->getConfig($storeConfig);
    }

    public function generateUniqueAuthKey($lenght = 32)
    {
        $authKey = $this->randomString;

        return $authKey->getRandomString($lenght);
    }

    public function generateSecretAuthKey($lenght = 32)
    {
        $secrteAuthKey = $this->randomString;

        return $secrteAuthKey->getRandomString($lenght);
    }
}
