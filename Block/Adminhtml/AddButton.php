<?php

namespace Eadesigndev\ComposerRepo\Block\Adminhtml;

use Magento\Backend\Block\Widget\Container;
use Magento\Backend\Block\Widget\Context;
use Magento\Customer\Model\Customer;

/**
 * Class AddButton
 * @package Eadesigndev\ComposerRepo\Block\Adminhtml
 */
class AddButton extends Container
{


    /**
     * AddButton constructor.
     * @param Context $context
     * @param EntityType $entityType
     * @param array $data
     */
    public function __construct(
        Context $context,
        EntityType $entityType,
        array $data = []
    ) {
        $this->entityType = $entityType;
        parent::__construct($context, $data);
    }

    /**
     * @return $this
     */
    public function _prepareLayout()
    {
        $addButtonProps = [
            'id' => 'add_new_template',
            'label' => __('Add Template'),
            'class' => 'add',
            'button_class' => '',
            'class_name' => 'Magento\Backend\Block\Widget\Button\SplitButton',
            'options' => $this->entityOptions(),
        ];
        $this->buttonList->add('add_new', $addButtonProps);

        return parent::_prepareLayout();
    }

    /**
     * @return array
     */
    public function entityOptions()
    {
        $splitButtonOptions = [];
        $types = $this->entityType->getAvailable();

        foreach ($types as $typeId => $type) {
            $splitButtonOptions[$typeId] = [
                'label' => $type,
                'onclick' => "setLocation('" . $this->entityUrl($typeId) . "')",
                'default' => Customer::ENTITY == $typeId,
            ];
        }

        return $splitButtonOptions;
    }

    /**
     * @param $typeId
     * @return string
     */
    private function entityUrl($typeId)
    {
        return $this->getUrl(
            '*/*/newentity',
            ['type' => $typeId]
        );
    }
}