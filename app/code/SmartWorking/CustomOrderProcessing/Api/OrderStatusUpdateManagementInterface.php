<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace SmartWorking\CustomOrderProcessing\Api;

interface OrderStatusUpdateManagementInterface
{

    /**
     * Update order status
     *
     * @param string $incrementId
     * @param string $status
     * @return string
     */
    public function postOrderStatusUpdate($incrementId, $status);
}
