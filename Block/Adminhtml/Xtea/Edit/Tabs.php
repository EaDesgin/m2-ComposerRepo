<?php

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
    public function _construct()
    {
        parent::_construct();
        $this->setId('xtea_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Field Composer Package'));
    }

    protected function _beforeToHtml()
    {
//        $this->addTab(
//            'labels',
//            [
//                'label' => __('Label/Option'),
//                'title' => __('Label/Option'),
//                'content' => $this->getChildHtml('labels')
//            ]
//        );

        return parent::_beforeToHtml();
    }
}