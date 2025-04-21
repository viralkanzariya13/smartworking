<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace SmartWorking\CustomOrderProcessing\Model\ResourceModel\OrderStatusLog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /** @var $_idFieldName */
    protected $_idFieldName = 'entity_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \SmartWorking\CustomOrderProcessing\Model\OrderStatusLog::class,
            \SmartWorking\CustomOrderProcessing\Model\ResourceModel\OrderStatusLog::class
        );
    }
}
