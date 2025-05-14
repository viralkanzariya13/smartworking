<?php
use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\Order\Status\HistoryFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Model\QuoteManagement;
use Magento\TestFramework\Helper\Bootstrap;

$objectManager = Bootstrap::getObjectManager();

/** @var StoreManagerInterface $storeManager */
$store = $objectManager->get(\Magento\Store\Model\StoreManagerInterface::class)->getStore();

/** Create a customer */
$customer = $objectManager->create(\Magento\Customer\Model\Customer::class);
$customer->setWebsiteId($store->getWebsiteId())
    ->setEmail('test@example.com')
    ->setFirstname('John')
    ->setLastname('Doe')
    ->setPassword('password')
    ->save();

/** Create a quote */
$quote = $objectManager->create(\Magento\Quote\Model\Quote::class);
$quote->setStore($store);
$quote->setIsActive(false);
$quote->assignCustomer($customer);

/** Add product to quote */
$product = $objectManager->create(\Magento\Catalog\Model\Product::class)->load(1); // You must ensure product with ID 1 exists
$quote->addProduct($product, 1);

$quote->getBillingAddress()->addData([
    'firstname' => 'John',
    'lastname' => 'Doe',
    'street' => '123 Main St',
    'city' => 'Los Angeles',
    'postcode' => '90001',
    'telephone' => '1234567890',
    'country_id' => 'US',
    'region' => 'California',
]);

$quote->getShippingAddress()->addData([
    'firstname' => 'John',
    'lastname' => 'Doe',
    'street' => '123 Main St',
    'city' => 'Los Angeles',
    'postcode' => '90001',
    'telephone' => '1234567890',
    'country_id' => 'US',
    'region' => 'California',
    'shipping_method' => 'flatrate_flatrate',
]);

$quote->getShippingAddress()->setCollectShippingRates(true)->collectShippingRates();
$quote->setPaymentMethod('checkmo');
$quote->setInventoryProcessed(false);
$quote->getPayment()->importData(['method' => 'checkmo']);

$quote->collectTotals()->save();

/** Place the order */
$order = $objectManager->create(\Magento\Quote\Model\QuoteManagement::class)->submit($quote);
$order->setIncrementId('100000001');
$order->save();
