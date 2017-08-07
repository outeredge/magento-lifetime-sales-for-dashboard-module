<?php

namespace OuterEdge\LifetimeSalesForDashboard\Controller\Adminhtml\Dashboard;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use OuterEdge\LifetimeSalesForDashboard\Helper\Data;

class Index extends Action
{   
    /**
     * @var Data $helper
     */
    protected $helper;

    /**
     * @param Context     $context
     * @param Data        $helper
     */
    public function __construct(
        Context $context,
        Data $helper
    ) {
        $this->helper = $helper;
        
        parent::__construct($context);
    }

    /**
     *
     * @return type
     */
    public function execute()
    {
        return $this->helper->sendLifetimeSales();
    }
}
