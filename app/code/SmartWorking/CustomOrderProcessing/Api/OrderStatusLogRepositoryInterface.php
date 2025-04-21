<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace SmartWorking\CustomOrderProcessing\Api;

interface OrderStatusLogRepositoryInterface
{

    /**
     * Save Order Status Log
     *
     * @param \SmartWorking\CustomOrderProcessing\Api\Data\OrderStatusLogInterface $orderstatuslog
     * @return \SmartWorking\CustomOrderProcessing\Api\Data\OrderStatusLogInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \SmartWorking\CustomOrderProcessing\Api\Data\OrderStatusLogInterface $orderstatuslog
    );

    /**
     * Retrieve Order Status Log
     *
     * @param string $orderstatuslogId
     * @return \SmartWorking\CustomOrderProcessing\Api\Data\OrderStatusLogInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($orderstatuslogId);

    /**
     * Retrieve Order Status Log matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \SmartWorking\CustomOrderProcessing\Api\Data\OrderStatusLogSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Order Status Log
     *
     * @param \SmartWorking\CustomOrderProcessing\Api\Data\OrderStatusLogInterface $orderstatuslog
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \SmartWorking\CustomOrderProcessing\Api\Data\OrderStatusLogInterface $orderstatuslog
    );

    /**
     * Delete Order Status Log by ID
     *
     * @param string $orderstatuslogId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($orderstatuslogId);
}
