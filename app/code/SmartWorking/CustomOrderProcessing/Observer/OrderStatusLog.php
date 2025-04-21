<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace SmartWorking\CustomOrderProcessing\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use SmartWorking\CustomOrderProcessing\Helper\Mail as OrderProcessingMail;
use SmartWorking\CustomOrderProcessing\Model\OrderStatusLogFactory;

class OrderStatusLog implements ObserverInterface
{
    public const EMAIL_SENDER = 'trans_email/ident_custom1/email';

    /**
     * @param OrderStatusLogFactory $orderStatusLogFactory
     * @param OrderProcessingMail $orderProcessingMail
     */
    public function __construct(
        protected OrderStatusLogFactory $orderStatusLogFactory,
        protected OrderProcessingMail $orderProcessingMail
    ) {
    }

    /**
     * Log New Order Status and Send Shipment Email
     *
     * @param object $observer
     */
    public function execute(Observer $observer)
    {
        try {
            $order = $observer->getEvent()->getOrder();
            $origData = $order->getOrigData();
            $oldStatus = $origData['status'] ?? null;
            $newStatus = $order->getStatus();

            if ($oldStatus && $newStatus && $oldStatus !== $newStatus) {
                $log = $this->orderStatusLogFactory->create();
                $log->setData([
                    'order_id'   => $order->getId(),
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'timestamp'  => (new \DateTime())->format('Y-m-d H:i:s'),
                ]);
                $log->save();

                /* Send email to customer if the order is fully shipped */
                $isFullyShipped = true;

                foreach ($order->getAllItems() as $item) {
                    if ($item->getQtyShipped() < $item->getQtyOrdered()) {
                        $isFullyShipped = false;
                        break;
                    }
                }

                if ($isFullyShipped) {
                    $toArray = [
                        'email' => $order->getCustomerEmail(),
                        'name'  => $order->getCustomerName()
                    ];
                    $this->orderProcessingMail->sendOrderStatusUpdateEmail(
                        $order,
                        self::EMAIL_SENDER,
                        $toArray
                    );
                }
            }
        } catch (\Exception $e) {
            // You can log the error or handle it differently if needed
            $this->logger->error('Order status observer error: ' . $e->getMessage());
        }
    }
}
