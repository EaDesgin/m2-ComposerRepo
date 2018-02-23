<?php

namespace Eadesigndev\ComposerRepo\Model\Sources;

/**
 * Class InputType
 * @package Eadesigndev\ComposerRepo\ModelSource
 */
class PackageType extends AbstractSource
{
    const NORMAL = 1;
    const BUNDLED = 2;

    public function getAvailable()
    {
        return [
            self::NORMAL => __('Normal(Payed)'),
            self::BUNDLED => __('Bundled(Library)'),
        ];
    }
}