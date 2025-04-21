<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace SmartWorking\CustomOrderProcessing\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Mail extends AbstractHelper
{
    /** @var TransportBuilder $transportBuilder */
    protected $transportBuilder;

    /** @var StoreManagerInterface $storeManager */
    protected $storeManager;

    /** @var ScopeConfigInterface $scopeConfig */
    protected $scopeConfig;

    /** @var StateInterface $inlineTranslation */
    protected $inlineTranslation;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param StateInterface $inlineTranslation
     */
    public function __construct(
        Context $context,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        StateInterface $inlineTranslation
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->inlineTranslation = $inlineTranslation;
    }

    /**
     * Send shipment email
     *
     * @param object $order
     * @param string $template configuration path of email template
     * @param string $sender configuration path of email identity
     * @param array $to email and name of the receiver
     * @param array $templateParams
     * @param int|null $storeId
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function sendEmailTemplate(
        $order,
        $template,
        $sender,
        $to = [],
        $templateParams = [],
        $storeId = null
    ) {
        if (!isset($to['email']) || empty($to['email'])) {
            throw new LocalizedException(
                __('We could not send the email because the receiver data is invalid.')
            );
        }
        $storeId = $storeId ? $storeId : $this->storeManager->getStore()->getId();
        $name = isset($to['name']) ? $to['name'] : '';

        $this->inlineTranslation->suspend();

        /** @var \Magento\Framework\Mail\TransportInterface $transport */
        $transport = $this->transportBuilder->setTemplateIdentifier(
            $this->scopeConfig->getValue($template, ScopeInterface::SCOPE_STORE, $storeId)
        )->setTemplateOptions(
            ['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => $storeId]
        )->setTemplateVars([
            'order' => $order
        ])->setScopeId(
            $storeId
        )->setFrom(
            $this->scopeConfig->getValue($sender, ScopeInterface::SCOPE_STORE, $storeId)
        )->addTo(
            $to['email'],
            $name
        )->getTransport();

        $transport->sendMessage();
        $this->inlineTranslation->resume();
    }

    /**
     * Send the Order Status Update Email
     *
     * @param object $order
     * @param string $sender configuration path of email identity
     * @param array $to email and name of the receiver
     * @return void
     */
    public function sendOrderStatusUpdateEmail(
        $order,
        $sender,
        $to
    ): void {
        $this->sendEmailTemplate(
            $order,
            'customorderprocessing/general/order_status_update',
            $sender,
            $to
        );
    }
}
