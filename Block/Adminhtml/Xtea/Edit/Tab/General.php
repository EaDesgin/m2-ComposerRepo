<?php

namespace Eadesigndev\ComposerRepo\Block\Adminhtml\Xtea\Edit\Tab;

use Eadesigndev\ComposerRepo\Model\ComposerRepo;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Eadesigndev\ComposerRepo\Model\Sources\InputType;
use Eadesigndev\ComposerRepo\Model\Sources\PackageType;
use Magento\Framework\Data\Form;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Phrase;
use Magento\Framework\Registry;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Config\Model\Config\Source\Yesno;
use Magento\Store\Model\System\Store as SystemStore;
use Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element;

/**
 * Class Main
 * @package Eadesigndev\ComposerRepo\Block\Adminhtml\Xtea\Edit\Tab
 */
class General extends Generic implements TabInterface
{
    /**
     * @var PackageType
     */
    private $packageType;

    /**
     * @var Yesno
     */
    private $yesNo;

    /**
     * @var SystemStore
     */
    private $systemStore;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var InputType
     */
    private $inputType;

    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Yesno $yesNo,
        SystemStore $systemStore,
        InputType $inputType,
        PackageType $packageType,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->yesNo = $yesNo;
        $this->systemStore = $systemStore;
        $this->packageType = $packageType;
        $this->inputType = $inputType;


        parent::__construct($context, $registry, $formFactory, $data, $inputType, $packageType);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function _prepareForm()
    {

        /** @var ComposerRepo $model */
        $model = $this->registry->registry('composer_data');

        /** @var Form $form */
        $form = $this->_formFactory->create();


        $fieldSet = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Package Information')]
        );

        $types = $this->inputType->getAvailable();

        $fieldSet->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Package Name'),
                'title' => __('Package Name'),
                'values' => $types,
                'note' => __('Composer package name [vender]/[module-name]'),
                'required' => true,
            ]
        );

        $fieldSet->addField(
            'status',
            'select',
            [
                'name' => 'status',
                'label' => __('Status'),
                'title' => __('Status'),
                'values' => $this->yesNo->toOptionArray(),
                'required' => true,
            ]
        );

        $types = $this->packageType->getAvailable();

        $fieldSet->addField(
            'bundled_package',
            'select',
            [
                'name' => 'bundled_package',
                'label' => __('Package Type'),
                'title' => __('Package Type'),
                'values' => $types,
                'note' => __('Bundled packages will always be available in the packages.json, this can be useful for required library packages'),
                'required' => true,
            ]
        );

        $fieldSet->addField(
            'description',
            'text',
            [
                'name' => 'description',
                'label' => __('Package Title'),
                'title' => __('Package Title'),
                'values' => $types,
                'note' => __('Used to describe package in customer menu'),
                'required' => true,
            ]
        );

        $fieldSet->addField(
            'product_id',
            'text',
            [
                'name' => 'product_id',
                'label' => __('Magento Product ID'),
                'title' => __('Magento Product ID'),
                'values' => $types,
                'required' => true,
            ]
        );

        $fieldSet->addField(
            'repository_url',
            'text',
            [
                'name' => 'repository_url',
                'label' => __('Repository URL'),
                'title' => __('Repository URL'),
                'values' => $types,
                'required' => true,
            ]
        );

        $fieldSet->addField(
            'repository_options',
            'textarea',
            [
                'name' => 'repository_options',
                'label' => __('Repository options'),
                'title' => __('Repository options'),
                'values' => $types,
                'note' => __('Repository extra parameters in JSON format'),
                'required' => false,
            ]
        );

        if ($model->getId()) {
            $fieldSet->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        }

        $form->setValues($model->getData());
        $this->setForm($form);

        parent::_prepareForm();

        return $this;
    }

    /**
     * @return Phrase
     */
    public function getTabLabel()
    {
        return __('Package Information');
    }

    /**
     * Prepare title for tab
     *
     * @return Phrase
     */
    public function getTabTitle()
    {
        return __('Package Information');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }


}