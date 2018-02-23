<?php
namespace Eadesigndev\ComposerRepo\Model\Sources;
/**
 * Class InputType
 * @package Eadesigndev\ComposerRepo\ModelSource
 */
class InputType extends AbstractSource
{
    public function getAvailable()
    {
        return[
            'text' => __('Text Field'),
            'textarea' => __('Text Area'),
            'date' => __('Date & Time'),
            'boolean' => __('Yes/No'),
            'multiselect' => __('Multiple Select'),
            'select' => __('Dropdown'),
        ];
    }
}