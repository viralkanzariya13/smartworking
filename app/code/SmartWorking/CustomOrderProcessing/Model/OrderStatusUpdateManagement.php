<?php
declare(strict_types=1);

namespace SmartWorking\CustomOrderProcessing\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\App\CacheInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory as StatusCollectionFactory;
use SmartWorking\CustomOrderProcessing\Api\OrderStatusUpdateManagementInterface;

class OrderStatusUpdateManagement implements OrderStatusUpdateManagementInterface
{
    private const CACHE_ID = 'smartworking_order_statuses';
    private const CACHE_TTL = 86400; // 24 hours

    /**
     * Cached list of valid order statuses for this request
     *
     * @var array|null
     */
    private ?array $cachedOrderStatuses = null;

    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected StatusCollectionFactory $statusCollectionFactory,
        protected CacheInterface $cache
    ) {
    }

    public function postOrderStatusUpdate($incrementId, $orderStatus)
    {
        $order = $this->orderRepository->get($incrementId);
        if (!$order->getEntityId()) {
            throw new LocalizedException(__('Order does not exist.'));
        }

        if ($this->cachedOrderStatuses === null) {
            $cached = $this->cache->load(self::CACHE_ID);
            if ($cached !== false) {
                $this->cachedOrderStatuses = json_decode($cached, true);
            } else {
                $statusCollection = $this->statusCollectionFactory->create();
                $statusCollection->addFieldToSelect(['status']);
                $this->cachedOrderStatuses = $statusCollection->getColumnValues('status');
                $this->cache->save(json_encode($this->cachedOrderStatuses), self::CACHE_ID, [], self::CACHE_TTL);
            }
        }

        if (!in_array($orderStatus, $this->cachedOrderStatuses)) {
            throw new LocalizedException(__('Please provide valid order status.'));
        }

        $order->setStatus($orderStatus);
        $this->orderRepository->save($order);

        return 'Order status updated to ' . $orderStatus;
    }
}
