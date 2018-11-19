<?php

namespace Eadesigndev\ComposerRepo\Model\Sources;

/**
 * Class InputType
 * @package Eadesigndev\ComposerRepo\ModelSource
 */
class EmailSender extends AbstractSource
{
    const GENERAL = 0;
    const SALES = 1;
    const CUSTOMER = 2;
    const CUSTOM_EMAIL = 3;
    const CUSTOM_EMAIL_1 = 4;

    public function getAvailable()
    {
        return [
            self::GENERAL => __('General Contact'),
            self::SALES => __('Sales Representative'),
            self::CUSTOMER => __('Customer Support'),
            self::CUSTOM_EMAIL => __('Custom Email 1'),
            self::CUSTOM_EMAIL_1 => __('Custom Email 2'),
        ];
    }
}
