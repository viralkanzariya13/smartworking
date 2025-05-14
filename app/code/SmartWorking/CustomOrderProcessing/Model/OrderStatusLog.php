<?php
declare(strict_types=1);

namespace SmartWorking\CustomOrderProcessing\Model;

use Magento\Framework\Model\AbstractModel;
use SmartWorking\CustomOrderProcessing\Api\Data\OrderStatusLogInterface;
use Magento\Framework\DataObject\IdentityInterface;

class OrderStatusLog extends AbstractModel implements OrderStatusLogInterface, IdentityInterface
{
    const CACHE_TAG = 'smartworking_order_status_log';

    protected $_cacheTag = self::CACHE_TAG;

    protected function _construct()
    {
        $this->_init(\SmartWorking\CustomOrderProcessing\Model\ResourceModel\OrderStatusLog::class);
    }

    public function getEntityId()
    {
        return $this->getData(self::ORDERSTATUSLOG_ID);
    }

    public function setEntityId($orderstatuslogId)
    {
        return $this->setData(self::ORDERSTATUSLOG_ID, $orderstatuslogId);
    }

    public function getOrderID()
    {
        return $this->getData(self::ORDER_ID);
    }

    public function setOrderID($orderID)
    {
        return $this->setData(self::ORDER_ID, $orderID);
    }

    public function getOldStatus()
    {
        return $this->getData(self::OLD_STATUS);
    }

    public function setOldStatus($oldStatus)
    {
        return $this->setData(self::OLD_STATUS, $oldStatus);
    }

    public function getNewStatus()
    {
        return $this->getData(self::NEW_STATUS);
    }

    public function setNewStatus($newStatus)
    {
        return $this->setData(self::NEW_STATUS, $newStatus);
    }

    public function getTimestamp()
    {
        return $this->getData(self::DATE);
    }

    public function setTimestamp($date)
    {
        return $this->setData(self::DATE, $date);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
