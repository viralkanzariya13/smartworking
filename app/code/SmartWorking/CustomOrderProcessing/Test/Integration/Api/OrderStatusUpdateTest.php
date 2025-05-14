<?php
declare(strict_types=1);

namespace SmartWorking\CustomOrderProcessing\Test\Integration\Api;

use Magento\TestFramework\TestCase\AbstractController as AbstractControllerTestCase;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;

/**
 * @magentoDataFixture SmartWorking/CustomOrderProcessing/Test/Integration/_files/order.php
 */
class OrderStatusUpdateTest extends \PHPUnit\Framework\TestCase
{
    private $orderRepository;
    private $token;

    protected function setUp(): void
    {
        $this->orderRepository = Bootstrap::getObjectManager()->get(OrderRepositoryInterface::class);

        // Generate admin token (assuming integration tests are using default admin credentials)
        $this->token = $this->getAdminToken();
    }

    public function testOrderStatusUpdate(): void
    {
        // Load order fixture (you need to have an order fixture with increment_id "100000001")
        $order = $this->orderRepository->get('100000001');
        $originalStatus = $order->getStatus();

        // Prepare API call
        $client = new \Zend_Http_Client(Bootstrap::getInstance()->getBackendUrl() . 'rest/V1/smartworking-customorderprocessing/orderstatusupdate');
        $client->setHeaders(['Authorization' => 'Bearer ' . $this->token]);
        $client->setMethod(\Zend_Http_Client::POST);
        $client->setEncType('application/json');
        $client->setRawData(json_encode([
            'order_increment_id' => '100000001',
            'new_status' => 'processing'
        ]));

        $response = $client->request();
        $this->assertEquals(200, $response->getStatus());

        // Reload and assert status
        $updatedOrder = $this->orderRepository->get('100000001');
        $this->assertEquals('processing', $updatedOrder->getStatus());

        // Optionally revert the status
        $updatedOrder->setStatus($originalStatus);
        $this->orderRepository->save($updatedOrder);
    }

    private function getAdminToken(): string
    {
        $client = new \Zend_Http_Client(Bootstrap::getInstance()->getBackendUrl() . 'rest/V1/integration/admin/token');
        $client->setHeaders(['Content-Type' => 'application/json']);
        $client->setMethod(\Zend_Http_Client::POST);
        $client->setRawData(json_encode([
            'username' => 'admin',
            'password' => 'admin123' // Replace with your integration test admin password
        ]));

        $response = $client->request();
        return json_decode($response->getBody(), true);
    }
}