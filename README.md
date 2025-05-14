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

🧪 Integration Tests Setup
Step 1: Register Test Directory
Edit file:

MagentoRoot/dev/tests/integration/phpunit.xml.dist
Add inside <testsuites> block:

<testsuite name="SmartWorking CustomOrderProcessing Integration Tests">
  <directory suffix="Test.php">../../app/code/SmartWorking/CustomOrderProcessing/Test/Integration</directory>
</testsuite>

Step 2: Fixture File
Path:

app/code/SmartWorking/CustomOrderProcessing/Test/Integration/_files/order.php
To Customize:

Line where increment_id is set:

$order->setIncrementId('100000001'); // <-- Change if needed
Line where product_id is assigned:

$orderItem->setProductId(1); // <-- Change to your product ID 

📄 Sample Test File
SmartWorking\CustomOrderProcessing\Test\Integration\Api\OrderStatusUpdateTest.php

Automatically loads fixture from _files/order.php

Posts JSON to the custom endpoint

Asserts status code and DB change

To run the test:

cd dev/tests/integration
../../../vendor/bin/phpunit

## Improved Caching

    ✅ CacheInterface used in OrderStatusUpdateManagement.php

    ✅ Cache tag and identity implemented in OrderStatusLog.php

    ✅ di.xml updated to inject CacheInterface

    ✅ Status list is now cached in OrderStatusUpdateManagement

    ✅ Identities implemented for cache invalidation

    ✅ Avoids reloading order unnecessarily

    ✅ Only minimal fields are queried (status)

    ✅ getList() in repository is optimized with collectionProcessor

    ✅ Caching in save() and get().

    ✅ Cache invalidation in delete().

    ✅ Proper use of CacheInterface with a unique tag and lifetime.