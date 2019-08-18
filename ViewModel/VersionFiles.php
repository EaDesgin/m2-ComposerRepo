<?php
/**
 * VersionFiles
 *
 * @copyright Copyright © 2019 EAdesign by Eco Active S.R.L.. All rights reserved.
 * @author    office@eadesign.ro
 */

declare(strict_types = 1);

namespace Eadesigndev\ComposerRepo\ViewModel;

use Eadesigndev\ComposerRepo\Api\VersionRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;
use Eadesigndev\ComposerRepo\Model\ComposerRepo;

class VersionFiles implements  ArgumentInterface
{
    /** @var Json */
    private $json;
    
    public function __construct(Json $json) {
        $this->json = $json;
    }

    public function versionsByPackage(\Eadesigndev\ComposerRepo\Model\ComposerRepo $package): array
    {
        $pakageFile = $package->getData('package_json');
        $decoded = $this->json->unserialize($pakageFile);
        
        $tableArray = [];

        foreach ($decoded as $version => $details) {
            if (strpos($version, 'dev') !== false) {
                continue;
            }
            if (isset($details['dist']) && isset($details['dist']['url'])) {
                $tableArray[$version] = $details['dist']['url'];
            }
        }
        
        return $tableArray;
    }
}
