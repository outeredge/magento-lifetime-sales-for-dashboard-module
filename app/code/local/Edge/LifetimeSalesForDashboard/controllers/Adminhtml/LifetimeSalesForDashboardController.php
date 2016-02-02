<?php

class Edge_LifetimeSalesForDashboard_Adminhtml_LifetimeSalesForDashboardController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/config/sales');
    }
    
    public function forceAction()
    {
        try {
            Mage::getModel('lifetimesales/report')->sendLifetimeSales();
            Mage::getSingleton('core/session')->addSuccess('Lifetime sales stats were successfully pushed to server.');
        } catch (Exception $e){
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }        
        $this->_redirectReferer();
    }
}
