<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace SmartWorking\CustomOrderProcessing\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use SmartWorking\CustomOrderProcessing\Api\Data\OrderStatusLogInterface;
use SmartWorking\CustomOrderProcessing\Api\Data\OrderStatusLogInterfaceFactory;
use SmartWorking\CustomOrderProcessing\Api\Data\OrderStatusLogSearchResultsInterfaceFactory;
use SmartWorking\CustomOrderProcessing\Api\OrderStatusLogRepositoryInterface;
use SmartWorking\CustomOrderProcessing\Model\ResourceModel\OrderStatusLog as ResourceOrderStatusLog;
use SmartWorking\CustomOrderProcessing\Model\ResourceModel\OrderStatusLog\CollectionFactory as LogCollectionFactory;

class OrderStatusLogRepository implements OrderStatusLogRepositoryInterface
{

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var LogCollectionFactory
     */
    protected $orderstatuslogCollectionFactory;

    /**
     * @var ResourceOrderStatusLog
     */
    protected $resource;

    /**
     * @var OrderStatusLog
     */
    protected $searchResultsFactory;

    /**
     * @var OrderStatusLogInterfaceFactory
     */
    protected $orderstatuslogFactory;

    /**
     * @param ResourceOrderStatusLog $resource
     * @param OrderStatusLogInterfaceFactory $orderstatuslogFactory
     * @param LogCollectionFactory $orderstatuslogCollectionFactory
     * @param OrderStatusLogSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceOrderStatusLog $resource,
        OrderStatusLogInterfaceFactory $orderstatuslogFactory,
        LogCollectionFactory $orderstatuslogCollectionFactory,
        OrderStatusLogSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->orderstatuslogFactory = $orderstatuslogFactory;
        $this->orderstatuslogCollectionFactory = $orderstatuslogCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(OrderStatusLogInterface $orderstatuslog)
    {
        try {
            $this->resource->save($orderstatuslog);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the orderstatuslog: %1',
                $exception->getMessage()
            ));
        }
        return $orderstatuslog;
    }

    /**
     * @inheritDoc
     */
    public function get($orderstatuslogId)
    {
        $orderstatuslog = $this->orderstatuslogFactory->create();
        $this->resource->load($orderstatuslog, $orderstatuslogId);
        if (!$orderstatuslog->getId()) {
            throw new NoSuchEntityException(__('OrderStatusLog with id "%1" does not exist.', $orderstatuslogId));
        }
        return $orderstatuslog;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->orderstatuslogCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(OrderStatusLogInterface $orderstatuslog)
    {
        try {
            $orderstatuslogModel = $this->orderstatuslogFactory->create();
            $this->resource->load($orderstatuslogModel, $orderstatuslog->getOrderStatusLogId());
            $this->resource->delete($orderstatuslogModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the OrderStatusLog: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($orderstatuslogId)
    {
        return $this->delete($this->get($orderstatuslogId));
    }
}
