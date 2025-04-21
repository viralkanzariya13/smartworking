<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace SmartWorking\CustomOrderProcessing\Model;

use Magento\Framework\Model\AbstractModel;
use SmartWorking\CustomOrderProcessing\Api\Data\OrderStatusLogInterface;

class OrderStatusLog extends AbstractModel implements OrderStatusLogInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\SmartWorking\CustomOrderProcessing\Model\ResourceModel\OrderStatusLog::class);
    }

    /**
     * @inheritDoc
     */
    public function getEntityId()
    {
        return $this->getData(self::ORDERSTATUSLOG_ID);
    }

    /**
     * @inheritDoc
     */
    public function setEntityId($orderstatuslogId)
    {
        return $this->setData(self::ORDERSTATUSLOG_ID, $orderstatuslogId);
    }

    /**
     * @inheritDoc
     */
    public function getOrderID()
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setOrderID($orderID)
    {
        return $this->setData(self::ORDER_ID, $orderID);
    }

    /**
     * @inheritDoc
     */
    public function getOldStatus()
    {
        return $this->getData(self::OLD_STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setOldStatus($oldStatus)
    {
        return $this->setData(self::OLD_STATUS, $oldStatus);
    }

    /**
     * @inheritDoc
     */
    public function getNewStatus()
    {
        return $this->getData(self::NEW_STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setNewStatus($newStatus)
    {
        return $this->setData(self::NEW_STATUS, $newStatus);
    }

    /**
     * @inheritDoc
     */
    public function getTimestamp()
    {
        return $this->getData(self::DATE);
    }

    /**
     * @inheritDoc
     */
    public function setTimestamp($date)
    {
        return $this->setData(self::DATE, $date);
    }
}
