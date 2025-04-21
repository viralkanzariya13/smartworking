# Mage2 Module SmartWorking CustomOrderProcessing

    ``smartworking/module-customorderprocessing``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
Custom Order status change, log status changes in custom table & event based email notification.

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/SmartWorking`
 - Enable the module by running `php bin/magento module:enable SmartWorking_CustomOrderProcessing`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require smartworking/module-customorderprocessing`
 - enable the module by running `php bin/magento module:enable SmartWorking_CustomOrderProcessing`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Admin UI

 - Admin > SMART WORKING > Order Status Log


## Specifications

 - API Endpoint
    - POST - SmartWorking\CustomOrderProcessing\Api\OrderStatusUpdateManagementInterface > SmartWorking\CustomOrderProcessing\Model\OrderStatusUpdateManagement

 - Observer
    - sales_order_save_after > SmartWorking\CustomOrderProcessing\Observer\Webapi\Sales\OrderSaveAfter


## Unit Test

- vendor/bin/phpunit -c dev/tests/unit/phpunit.xml.dist app/code/SmartWorking/CustomOrderProcessing/Test/Unit/UpdateOrderStatusTest.php
 
## Rate Limiting

- $ bin/magento setup:config:set \
    --backpressure-logger=redis \
    --backpressure-logger-redis-server=127.0.0.1 \
    --backpressure-logger-redis-port=9345 \
    --backpressure-logger-redis-timeout=1 \
    --backpressure-logger-redis-persistent=persistent \
    --backpressure-logger-redis-db=3 \
    --backpressure-logger-redis-password=passw0rd@2k25\
    --backpressure-logger-redis-user=user042025 \
    --backpressure-logger-id-prefix=backpr_log   
