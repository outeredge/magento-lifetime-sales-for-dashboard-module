<?php

namespace OuterEdge\LifetimeSalesForDashboard\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface as Store;

class Data extends AbstractHelper
{
    
    protected $logFile = 'dashboard.log';
    
    /**
     * @var \Magento\Sales\Model\ResourceModel\Sale\CollectionFactory
     */
    protected $_saleCollectionFactory;

    /**
     * @param \Magento\Framework\App\Helper\Context                     $context
     * @param \Magento\Sales\Model\ResourceModel\Sale\CollectionFactory $saleCollectionFactory
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Sales\Model\ResourceModel\Sale\CollectionFactory $saleCollectionFactory
    ) {
        $this->_saleCollectionFactory = $saleCollectionFactory;
        
        parent::__construct($context);
    }
    
    public function sendLifetimeSales()
    {
        if (!$this->scopeConfig->getValue('lifetime_sales/config/enable', Store::SCOPE_STORE)) {
            return  ['valid' => false,
                     'message' => __('Is disabled')];
        }

        $uid = $this->scopeConfig->getValue('lifetime_sales/config/uid', Store::SCOPE_STORE);
        if (!$uid) {
            return  ['valid' => false,
                     'message' => __('UID is empty')];
        }

        $url = $this->scopeConfig->getValue('lifetime_sales/config/url', Store::SCOPE_STORE);
        if (!$url) {
            return  ['valid' => false,
                     'message' => __('URL is empty')];
        }

        $username = $this->scopeConfig->getValue('lifetime_sales/config/username', Store::SCOPE_STORE);
        $password = $this->scopeConfig->getValue('lifetime_sales/config/password', Store::SCOPE_STORE);
        
        //Get Sale LifeTime 
        $saleModel = $this->_saleCollectionFactory
            ->create()
            ->addFieldToFilter('status', array('in' => array(\Magento\Sales\Model\Order::STATE_PROCESSING, \Magento\Sales\Model\Order::STATE_COMPLETE)))
            ->setOrderStateFilter(\Magento\Sales\Model\Order::STATE_CANCELED, true)
            ->load();    
                
        $data = array(
            'uid' => $uid,
            'lifetime_sales' => $saleModel->getTotals()->getLifetime()
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
        
        if (curl_errno($ch)) {
            $error = 'Curl error: ' . curl_error($ch);
            $this->scopeConfig->getValue('lifetime_sales/config/logging', Store::SCOPE_STORE) ? Mage::log($error, null, $this->logFile) : false;
            return  ['valid' => false,
                     'message' => $error];
        }
        
        curl_close($ch);
        
        return [
            'valid' => true,
            'message' => "Refresh stats completed"];
    }
}