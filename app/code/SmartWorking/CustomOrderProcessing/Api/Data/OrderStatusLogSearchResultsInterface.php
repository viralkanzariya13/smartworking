<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace SmartWorking\CustomOrderProcessing\Api\Data;

interface OrderStatusLogSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get OrderStatusLog list.
     *
     * @return \SmartWorking\CustomOrderProcessing\Api\Data\OrderStatusLogInterface[]
     */
    public function getItems();

    /**
     * Set order_ID list.
     *
     * @param \SmartWorking\CustomOrderProcessing\Api\Data\OrderStatusLogInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
