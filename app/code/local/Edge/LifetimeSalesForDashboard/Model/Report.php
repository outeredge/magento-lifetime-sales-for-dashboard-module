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

        $username = Mage::getStoreConfig('sales/lifetime_sales/username');
        $password = Mage::getStoreConfig('sales/lifetime_sales/password');

        $data = array(
            'uid' => $uid,
            'lifetime_sales' => Mage::getResourceModel('reports/order_collection')->calculateSales()->load()->getFirstItem()->getLifetime()
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_exec($ch);
        
        if(curl_errno($ch)){
            $error = 'Curl error: ' . curl_error($ch);
            Mage::getStoreConfig('sales/lifetime_sales/logging') ? Mage::log($error, null, 'dashboard.log') : false;
        }
        
        curl_close($ch);
    }
}
