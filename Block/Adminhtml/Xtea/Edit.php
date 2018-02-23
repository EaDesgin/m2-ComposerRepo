<?php

namespace Eadesigndev\ComposerRepo\Block\Adminhtml\Xtea;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form\Container;
use Magento\Framework\Registry;

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
    public function _construct()
    {
        $this->_objectId = 'entity_id';
        $this->_blockGroup = 'Eadesigndev_ComposerRepo';
        $this->_controller = 'adminhtml_xtea';

        parent::_construct();

        $this->buttonList->update('save', 'label', __('Save'));

        $this->buttonList->update(
            'delete',
            'label',
            __('Delete Template')
        );
    }
}