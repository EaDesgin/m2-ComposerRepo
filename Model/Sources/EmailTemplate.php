<?php

namespace Eadesigndev\ComposerRepo\Model\Sources;

/**
 * Class InputType
 * @package Eadesigndev\ComposerRepo\ModelSource
 */
class EmailTemplate extends AbstractSource
{
    const COMPOSER = 0;
    const ORDER = 1;

    public function getAvailable()
    {
        return [
            self::COMPOSER => __('Composer Installation Instructions (Default Template from Locale)'),
            self::ORDER => __('Order Client'),
        ];
    }
}