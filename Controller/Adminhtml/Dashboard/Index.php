<?php

namespace OuterEdge\LifetimeSalesForDashboard\Controller\Adminhtml\Dashboard;

class Index extends \Magento\Backend\App\Action 
{
    protected $dashboard;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \OuterEdge\LifetimeSalesForDashboard\Model\Dashboard $dashboard
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \OuterEdge\LifetimeSalesForDashboard\Model\Dashboard $dashboard
    ) {
        $this->dashboard = $dashboard;

        parent::__construct($context);
    }

    public function execute(){
        die("hello outeredge");
        //TODO if works, call the model
    }
    
    public function forceAction()
    {
        try {
            $this->dashboard->sendLifetimeSales();
            Mage::getSingleton('core/session')->addSuccess('Lifetime sales stats were successfully pushed to server.');
        } catch (Exception $e){
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }
        $this->_redirectReferer();
    }
}
