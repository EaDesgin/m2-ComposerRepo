<?php

namespace Eadesigndev\ComposerRepo\Api;

use Eadesigndev\ComposerRepo\Api\Data\ComposerInterface;

interface ComposerRepositoryInterface
{

    /**
     * @param ComposerInterface $templates
     * @return mixed
     */
    public function save(ComposerInterface $templates);

    /**
     * @param $value the template id
     * @return mixed
     */
    public function getById($value);

    /**
     * @param ComposerInterface $templates
     * @return mixed
     */
    public function delete(ComposerInterface $templates);

    /**
     * @param $value the template id
     * @return mixed
     */
    public function deleteById($value);
}