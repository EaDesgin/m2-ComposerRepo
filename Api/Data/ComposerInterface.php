<?php
/**
 * Copyright © 2017 EaDesign by Eco Active S.R.L. All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\ComposerRepo\Api\Data;

interface ComposerInterface
{
    const ENTITY_ID = 'entity_id';
    const CREATE_DATE = 'createdate';
    const STATUS = 'status';
    const PRODUCT_ID = 'product_id';
    const NAME = 'name';
    const DESCRIPTION = 'description';
    const REPOSITORY_URL = 'repository_url';
    const REPOSITORY_OPTIONS = 'repository_options';
    const PACKAGE_JSON = 'package_json';
    const VERSION = 'version';
    const BUNDLED_PACKAGE = 'bundled_package';


    public function getId();

    public function getCreateDate();

    public function getStatus();

    public function getProductId();

    public function getName();

    public function getDescription();

    public function getRepositoryUrl();

    public function getRepositoryOption();

    public function getPackageJson();

    public function getVersion();

    public function getBundledPackage();



    public function setId($entityId);

    public function setCreateDate($createDate);

    public function setStatus($status);

    public function setProductId($productId);

    public function setName($name);

    public function setDescription($description);

    public function setRepositoryUrl($repositoryUrl);

    public function setRepositoryOption($repositoryOption);

    public function setPackageJson($packageJson);

    public function setVersion($version);

    public function setBundledPackage($bundledPackage);

}