<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace SmartWorking\CustomOrderProcessing\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory as StatusCollectionFactory;
use SmartWorking\CustomOrderProcessing\Api\OrderStatusUpdateManagementInterface;

class OrderStatusUpdateManagement implements OrderStatusUpdateManagementInterface
{
    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param StatusCollectionFactory $statusCollectionFactory
     */
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected StatusCollectionFactory $statusCollectionFactory
    ) {
    }

    /**
     * Validate and update order status
     *
     * @param int $incrementId
     * @param string $orderStatus
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function postOrderStatusUpdate($incrementId, $orderStatus)
    {
        $order = $this->orderRepository->get($incrementId);
        if (!$order->getEntityId()) {
            throw new LocalizedException(__('Order does not exist.'));
        }

        /* Check if given order status is invalid */
        $orderStatuses = $this->statusCollectionFactory->create();
        $orderStatuses->addFieldToSelect(['status']);

        $statusArray = $orderStatuses->getColumnValues('status');

        if (!in_array($orderStatus, $statusArray)) {
            throw new LocalizedException(__('Please provide valid order status.'));
        }

        $order->setStatus($orderStatus);
        $this->orderRepository->save($order);

        return 'Order status updated to ' . $orderStatus;
    }
}
