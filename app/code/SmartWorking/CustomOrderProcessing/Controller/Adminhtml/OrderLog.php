<?php
declare(strict_types=1);

namespace SmartWorking\CustomOrderProcessing\Controller\Adminhtml;

abstract class OrderLog extends \Magento\Backend\App\Action
{
    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('SmartWorking'), __('SmartWorking'))
            ->addBreadcrumb(__('Order Processing Log'), __('Order Processing Log'));
        return $resultPage;
    }
}
