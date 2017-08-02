<?php

namespace OuterEdge\LifetimeSalesForDashboard\Cron;
 
class RefreshStats
{
    /**
     * @var \OuterEdge\LifetimeSalesForDashboard\Helper\Data
     */
    protected $_helper;
 
    /**
     * @param \OuterEdge\LifetimeSalesForDashboard\Helper\Data $helper
     */
    public function __construct(
        \OuterEdge\LifetimeSalesForDashboard\Helper\Data $helper
    ) {
        $this->_helper = $helper;
    }
 
    /**
     * @param \Magento\Cron\Model\Schedule $schedule
     * @return void
     */
    public function execute(\Magento\Cron\Model\Schedule $schedule)
    {
        $this->_helper->sendLifetimeSales();
    }
}