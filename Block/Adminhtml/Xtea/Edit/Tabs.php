<?php
/**
 * Copyright © 2018 EaDesign by Eco Active S.R.L. All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\ComposerRepo\Block\Adminhtml\Xtea\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

/**
 * Admin page left menu
 */
class Tabs extends WidgetTabs
{
    /**
     * @return void
     */
    // @codingStandardsIgnoreLine
    public function _construct()
    {
        parent::_construct();
        $this->setId('xtea_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Field Composer Package'));
    }
}
