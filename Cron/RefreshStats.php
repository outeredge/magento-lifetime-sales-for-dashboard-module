<?php

namespace OuterEdge\LifetimeSalesForDashboard\Cron;
 
use OuterEdge\LifetimeSalesForDashboard\Helper\Data;

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
     * @return void
     */
    public function execute()
    {
        $this->helper->sendLifetimeSales();
    }
}
