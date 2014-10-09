<?php

class Edge_LifetimeSalesForDashboard_ForceController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
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