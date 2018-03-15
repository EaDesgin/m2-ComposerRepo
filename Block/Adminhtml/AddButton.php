<?php
/**
 * Copyright © 2018 EaDesign by Eco Active S.R.L. All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\ComposerRepo\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class AddButton extends Container
{
    /**
     * @return void;
     */
    public function _construct()
    {
        $this->_controller = 'adminhtml_composerrepo';
        $this->_blockGroup = 'Eadesigndev_ComposerRepo';

        parent::_construct();
        $this->buttonList->add(
            'add',
            [
                'label' => __('Packages'),
                'onclick' => "location.href='" . $this->getUrl('composer_packages/*/edit') . "'",
                'class' => 'apply'
            ]
        );
    }

    /**
     * @param $resourceId
     * @return bool
     */
    public function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}