<?php

class Edge_LifetimeSalesForDashboard_Model_Report
{
    public function sendLifetimeSales()
    {
        if (!Mage::getStoreConfig('sales/lifetime_sales/enabled')){
            return;
        }

        $uid = Mage::getStoreConfig('sales/lifetime_sales/uid');
        if (!$uid){
            return;
        }

        $url = Mage::getStoreConfig('sales/lifetime_sales/url');
        if (!$url){
            return;
        }

        $data = array(
            'uid' => $uid,
            'username' => Mage::getStoreConfig('sales/lifetime_sales/username'),
            'password' => Mage::getStoreConfig('sales/lifetime_sales/password'),
            'lifetime_sales' => Mage::getResourceModel('reports/order_collection')->calculateSales()->load()->getFirstItem()->getLifetime()
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_exec($ch);
        curl_close($ch);
    }
}
