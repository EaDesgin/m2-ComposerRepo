<?php

namespace Eadesigndev\ComposerRepo\Controller\Adminhtml\Fields;

use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Stdlib\DateTime\Filter\Date;

class PostDataProcessor
{
    private $dateFilter;

    private $messageManager;

    public function __construct(
        Date $dateFilter,
        ManagerInterface $messageManager
    ) {
        $this->dateFilter       = $dateFilter;
        $this->messageManager   = $messageManager;
    }

    /**
     * Filtering posted data. Converting localized data if needed
     *
     * @param array $data
     * @return array
     */
    public function filter($data)
    {
        $filterRules = [];

        foreach (['custom_theme_from', 'custom_theme_to'] as $dateField) {
            if (!empty($data[$dateField])) {
                $filterRules[$dateField] = $this->dateFilter;
            }
        }

        return (new \Zend_Filter_Input($filterRules, [], $data))->getUnescaped();
    }
}
