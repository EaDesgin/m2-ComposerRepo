<?php
/**
 * Copyright © 2018 EaDesign by Eco Active S.R.L. All rights reserved.
 * See LICENSE for license details.
 */

namespace Eadesigndev\ComposerRepo\Api\Data;

interface ComposerInterface
{
    const ENTITY_ID            = 'entity_id';
    const CREATED_AT           = 'created_at';
    const STATUS               = 'status';
    const PRODUCT_ID           = 'product_id';
    const NAME                 = 'name';
    const DESCRIPTION          = 'description';
    const REPOSITORY_URL       = 'repository_url';
    const REPOSITORY_OPTIONS   = 'repository_options';
    const PACKAGE_JSON         = 'package_json';
    const VERSION              = 'version';
    const BUNDLED_PACKAGE      = 'bundled_package';
    const DEFAULT              = 'default';
    const CUSTOMER_ID          = 'customer_id';
    const AUTH_KEY             = 'auth_key';
    const AUTH_SECRET          = 'auth_secret';
    const ORDER_ID             = 'order_id';
    const PACKAGE_ID           = 'package_id';
    const LAST_ALLOWED_VERSION = 'last_allowed_version';
    const LAST_ALLOWED_DATE    = 'last_allowed_date';
    const VERSION_ID           = 'version_id';
    const REMOTE_IP            = 'remote_ip';
    const FILE                 = 'file';



    public function getEntityId();

    public function getCreatedAt();

    public function getStatus();

    public function getProductId();

    public function getName();

    public function getDescription();

    public function getRepositoryUrl();

    public function getRepositoryOption();

    public function getPackageJson();

    public function getVersion();

    public function getBundledPackage();

    public function getDefault();

    public function getCustomerId();

    public function getAuthKey();

    public function getAuthSecret();

    public function getOrderId();

    public function getPackageId();

    public function getLastAllowedVersion();

    public function getLastAllowedDate();

    public function getVersionId();

    public function getRemoteIp();

    public function getFile();





    public function setEntityId($entityId);

    public function setCreatedAt($createdAt);

    public function setStatus($status);

    public function setProductId($productId);

    public function setName($name);

    public function setDescription($description);

    public function setRepositoryUrl($repositoryUrl);

    public function setRepositoryOption($repositoryOption);

    public function setPackageJson($packageJson);

    public function setVersion($version);

    public function setBundledPackage($bundledPackage);

    public function setDefault($default);

    public function setCustomerId($customerId);

    public function setAuthKey($authKey);

    public function setAuthSecret($authSecret);

    public function setOrderId($orderId);

    public function setPackageId($packageId);

    public function setLastAllowedVersion($lastAllowedVersion);

    public function setLastAllowedDate($lastAllowedDate);

    public function setVersionId($versionId);

    public function setRemoteIp($remoteIp);

    public function setFile($file);

}