<?php
declare(strict_types=1);

namespace SmartWorking\CustomOrderProcessing\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\App\CacheInterface;
use SmartWorking\CustomOrderProcessing\Api\Data\OrderStatusLogInterface;
use SmartWorking\CustomOrderProcessing\Api\Data\OrderStatusLogInterfaceFactory;
use SmartWorking\CustomOrderProcessing\Api\Data\OrderStatusLogSearchResultsInterfaceFactory;
use SmartWorking\CustomOrderProcessing\Api\OrderStatusLogRepositoryInterface;
use SmartWorking\CustomOrderProcessing\Model\ResourceModel\OrderStatusLog as ResourceOrderStatusLog;
use SmartWorking\CustomOrderProcessing\Model\ResourceModel\OrderStatusLog\CollectionFactory as LogCollectionFactory;

class OrderStatusLogRepository implements OrderStatusLogRepositoryInterface
{
    public const CACHE_TAG = 'smartworking_order_status_log';
    private const CACHE_LIFETIME = 3600;

    public function __construct(
        private readonly ResourceOrderStatusLog $resource,
        private readonly OrderStatusLogInterfaceFactory $orderStatusLogFactory,
        private readonly LogCollectionFactory $orderStatusLogCollectionFactory,
        private readonly OrderStatusLogSearchResultsInterfaceFactory $searchResultsFactory,
        private readonly CollectionProcessorInterface $collectionProcessor,
        private readonly CacheInterface $cache
    ) {}

    public function save(OrderStatusLogInterface $orderStatusLog): OrderStatusLogInterface
    {
        try {
            $this->resource->save($orderStatusLog);
            $cacheKey = self::CACHE_TAG . '_' . $orderStatusLog->getId();
            $this->cache->save(serialize($orderStatusLog), $cacheKey, [self::CACHE_TAG], self::CACHE_LIFETIME);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__('Could not save the OrderStatusLog: %1', $exception->getMessage()));
        }
        return $orderStatusLog;
    }

    public function get(int $orderStatusLogId): OrderStatusLogInterface
    {
        $cacheKey = self::CACHE_TAG . '_' . $orderStatusLogId;
        $cached = $this->cache->load($cacheKey);
        if ($cached) {
            return unserialize($cached);
        }

        $orderStatusLog = $this->orderStatusLogFactory->create();
        $this->resource->load($orderStatusLog, $orderStatusLogId);
        if (!$orderStatusLog->getId()) {
            throw new NoSuchEntityException(__('OrderStatusLog with id "%1" does not exist.', $orderStatusLogId));
        }

        $this->cache->save(serialize($orderStatusLog), $cacheKey, [self::CACHE_TAG], self::CACHE_LIFETIME);
        return $orderStatusLog;
    }

    public function getList(SearchCriteriaInterface $criteria)
    {
        $collection = $this->orderStatusLogCollectionFactory->create();
        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    public function delete(OrderStatusLogInterface $orderStatusLog): bool
    {
        try {
            $orderStatusLogModel = $this->orderStatusLogFactory->create();
            $this->resource->load($orderStatusLogModel, $orderStatusLog->getOrderStatusLogId());
            $this->resource->delete($orderStatusLogModel);

            $cacheKey = self::CACHE_TAG . '_' . $orderStatusLog->getOrderStatusLogId();
            $this->cache->remove($cacheKey);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__('Could not delete the OrderStatusLog: %1', $exception->getMessage()));
        }
        return true;
    }

    public function deleteById(int $orderStatusLogId): bool
    {
        return $this->delete($this->get($orderStatusLogId));
    }
}
