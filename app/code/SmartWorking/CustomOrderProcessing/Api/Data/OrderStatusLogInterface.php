<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace SmartWorking\CustomOrderProcessing\Api\Data;

interface OrderStatusLogInterface
{

    public const NEW_STATUS = 'new_status';
    public const ORDER_ID = 'order_id';
    public const OLD_STATUS = 'old_status';
    public const ORDERSTATUSLOG_ID = 'entity_id';
    public const DATE = 'timestamp';

    /**
     * Get entity_id
     *
     * @return string|null
     */
    public function getEntityId();

    /**
     * Set entity_id
     *
     * @param string $entityId
     * @return \SmartWorking\CustomOrderProcessing\OrderStatusLog\Api\Data\OrderStatusLogInterface
     */
    public function setEntityId($entityId);

    /**
     * Get order_id
     *
     * @return string|null
     */
    public function getOrderId();

    /**
     * Set order_id
     *
     * @param string $orderId
     * @return \SmartWorking\CustomOrderProcessing\OrderStatusLog\Api\Data\OrderStatusLogInterface
     */
    public function setOrderId($orderId);

    /**
     * Get old_status
     *
     * @return string|null
     */
    public function getOldStatus();

    /**
     * Set old_status
     *
     * @param string $oldStatus
     * @return \SmartWorking\CustomOrderProcessing\OrderStatusLog\Api\Data\OrderStatusLogInterface
     */
    public function setOldStatus($oldStatus);

    /**
     * Get new_status
     *
     * @return string|null
     */
    public function getNewStatus();

    /**
     * Set new_status
     *
     * @param string $newStatus
     * @return \SmartWorking\CustomOrderProcessing\OrderStatusLog\Api\Data\OrderStatusLogInterface
     */
    public function setNewStatus($newStatus);

    /**
     * Get datetime
     *
     * @return string|null
     */
    public function getTimestamp();

    /**
     * Set datetime
     *
     * @param string $date
     * @return \SmartWorking\CustomOrderProcessing\OrderStatusLog\Api\Data\OrderStatusLogInterface
     */
    public function setTimestamp($date);
}
