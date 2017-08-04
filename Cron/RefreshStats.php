<?php

namespace OuterEdge\LifetimeSalesForDashboard\Cron;
 
use OuterEdge\LifetimeSalesForDashboard\Helper\Data;
use Magento\Cron\Model\Schedule;

class RefreshStats
{
    /**
     * @var Data
     */
    protected $helper;
 
    /**
     * @param Data $helper
     */
    public function __construct(
        Data $helper
    ) {
        $this->helper = $helper;
    }
 
    /**
     * @param Schedule $schedule
     * @return void
     */
    public function execute(Schedule $schedule)
    {
        $this->helper->sendLifetimeSales();
    }
}