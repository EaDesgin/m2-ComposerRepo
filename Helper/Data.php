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
    const STORE_CONFIG   = 'eadesign_composerrepo/composerrepo_config/update_period';
    const REPO_NAME      = 'eadesign_composerrepo/composerrepo_config/repo_name';
    const REPO_URL       = 'eadesign_composerrepo/composerrepo_config/repo_url';
    const OUTPUT_DIR     = 'eadesign_composerrepo/composerrepo_satis/output_dir';
    const ARCHIVE_FORMAT = 'eadesign_composerrepo/composerrepo_satis_arhive/format';
    const ABSOLUTE_DIR    = 'eadesign_composerrepo/composerrepo_satis_arhive/abs_dir';
    const SATIS_BIN      = 'eadesign_composerrepo/composerrepo_satis/config_path';
    const SATIS_CFG      = 'eadesign_composerrepo/composerrepo_satis/command_path';
    const INCLUDE_DEV    = 'eadesign_composerrepo/composerrepo_config/dev_master';
    const OUTPUT_HTML    = 'magonex_composerrepo/composerrepo_satis/output_html';

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
     * @return string
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

    public function getConfigName($main = self::REPO_NAME)
    {
        return $this->getConfig($main);
    }

    public function getConfigUrl($repoUrl = self::REPO_URL)
    {
        return $this->getConfig($repoUrl);
    }

    public function getConfigOutput($outputDir = self::OUTPUT_DIR)
    {
        return $this->getConfig($outputDir);
    }

    public function getConfigArchive($archiveFormat = self::ARCHIVE_FORMAT)
    {
        return $this->getConfig($archiveFormat);
    }

    public function getConfigAbsoluteDir($archiveDir = self::ABSOLUTE_DIR)
    {
        return $this->getConfig($archiveDir);
    }

    public function getConfigSatisBin($satisBin = self::SATIS_BIN)
    {
        return $this->getConfig($satisBin);
    }

    public function getConfigSatisCfg($satisCfg = self::SATIS_CFG)
    {
        return $this->getConfig($satisCfg);
    }

    public function getConfigDevMaster($devMaster = self::INCLUDE_DEV)
    {
        return $this->getConfig($devMaster);
    }

    public function getConfigOutputHtml($outputHtml = self::OUTPUT_HTML)
    {
        return $this->getConfig($outputHtml);
    }
}
