<?php
/**
 * Copyright © 2018 EaDesign by Eco Active S.R.L. All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\ComposerRepo\Block\Adminhtml\Xtea;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;
use Magento\Backend\Block\Widget\Form\Container;

/**
 * Class Edit
 * @package Eadesigndev\ComposerRepo\Block\Adminhtml\Xtea
 */
class Edit extends Container
{
    /**
     * Core registry
     *
     * @var Registry
     *
     */
    private $coreRegistry = null;

    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     *
     * @return void
     */
    // @codingStandardsIgnoreLine
    public function _construct()
    {
        $this->_objectId = 'entity_id';
        $this->_blockGroup = 'Eadesigndev_ComposerRepo';
        $this->_controller = 'adminhtml_xtea';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save Package'));
        $this->buttonList->add(
            'saveandcontinue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
            ],
            -100
        );

        $this->buttonList->update(
            'delete',
            'label',
            __('Delete Package')
        );
    }
}
