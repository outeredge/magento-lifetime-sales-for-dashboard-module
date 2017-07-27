<?php

namespace OuterEdge\LifetimeSalesForDashboard\Model;

use Magento\Framework\Event\ObserverInterface;

class Dashboard implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->scopeConfig->getValue('sales/lifetime_sales/enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)){
            return;
        }

        $uid = $this->scopeConfig->getValue('sales/lifetime_sales/uid', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if (!$uid){
            return;
        }

        $url = $this->scopeConfig->getValue('sales/lifetime_sales/url', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if (!$url){
            return;
        }

        $username = $this->scopeConfig->getValue('sales/lifetime_sales/username', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $password = $this->scopeConfig->getValue('sales/lifetime_sales/password', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $data = array(
            'uid' => $uid,
            'lifetime_sales' => $this->scopeConfig->getValue('reports/order_collection', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)
                ->calculateSales()->load()->getFirstItem()->getLifetime()
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_exec($ch);
        
        if(curl_errno($ch)){
            $error = 'Curl error: ' . curl_error($ch);
            $this->scopeConfig->getValue('sales/lifetime_sales/logging', \Magento\Store\Model\ScopeInterface::SCOPE_STORE) ? Mage::log($error, null, 'dashboard.log') : false;
        }
        
        curl_close($ch);
    }
}
