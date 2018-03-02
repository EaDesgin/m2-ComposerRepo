<?php

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

        $this->_headerText = __('PDF Templates');
        $this->_addButtonLabel = __('Add New Template');
        parent::_construct();
        $this->buttonList->add(
            'template_apply',
            [
                'label' => __('Template'),
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