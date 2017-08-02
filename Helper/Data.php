<?php

namespace OuterEdge\LifetimeSalesForDashboard\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function sendLifetimeSales()
    {   
        if (!$this->scopeConfig->getValue('lifetime_sales/config/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)){
            return  ['valid' => false,
                     'message' => __('Is disabled')];
        }

        $uid = $this->scopeConfig->getValue('lifetime_sales/config/uid', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if (!$uid){
            return  ['valid' => false,
                     'message' => __('UID is empty')];
        }

        $url = $this->scopeConfig->getValue('lifetime_sales/config/url', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if (!$url){
            return  ['valid' => false,
                     'message' => __('URL is empty')];
        }

        $username = $this->scopeConfig->getValue('lifetime_sales/config/username', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $password = $this->scopeConfig->getValue('lifetime_sales/config/password', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

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
            $this->scopeConfig->getValue('lifetime_sales/config/logging', \Magento\Store\Model\ScopeInterface::SCOPE_STORE) ? Mage::log($error, null, 'dashboard.log') : false;
            return  ['valid' => false,
                     'message' => $error];
        }
        
        curl_close($ch);
        
        return [
            'valid' => true,
            'message' => "Refresh stats completed"];
    }
}