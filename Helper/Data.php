<?php

namespace OuterEdge\LifetimeSalesForDashboard\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface as Store;
use Magento\Sales\Model\ResourceModel\Sale\CollectionFactory;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\HTTP\Client\Curl;

class Data extends AbstractHelper
{
    /**
     * @var string
     */
    protected $logFile = 'dashboard.log';
    
    /**
     * @var CollectionFactory
     */
    protected $saleCollectionFactory;
    
    /**
    * @var Curl
    */
    protected $curlClient;

    /**
     * @param Context           $context
     * @param CollectionFactory $saleCollectionFactory
     * @param Curl              $curl
     */
    public function __construct(
        Context           $context,
        CollectionFactory $saleCollectionFactory,
        Curl              $curl
    ) {
        $this->saleCollectionFactory = $saleCollectionFactory;
        $this->curlClient            = $curl;
        
        parent::__construct($context);
    }
    
    /**
     * @return type
     */
    public function sendLifetimeSales()
    {
        if (!$this->scopeConfig->getValue(
            'lifetime_sales/config/enable',
            Store::SCOPE_STORE
        )) {
            return  ['valid' => false,
                     'message' => __('Is disabled')];
        }

        $uid = $this->scopeConfig->getValue(
            'lifetime_sales/config/uid',
            Store::SCOPE_STORE
        );
        if (!$uid) {
            return  ['valid' => false,
                     'message' => __('UID is empty')];
        }

        $url = $this->scopeConfig->getValue(
            'lifetime_sales/config/url',
            Store::SCOPE_STORE
        );
        if (!$url) {
            return  ['valid' => false,
                     'message' => __('URL is empty')];
        }

        $username = $this->scopeConfig->getValue(
            'lifetime_sales/config/username',
            Store::SCOPE_STORE
        );
        $password = $this->scopeConfig->getValue(
            'lifetime_sales/config/password',
            Store::SCOPE_STORE
        );
        
        //Get Sale LifeTime
        $saleModel = $this->saleCollectionFactory
            ->create()
            ->addFieldToFilter(
                'status',
                [
                'in' => [
                    \Magento\Sales\Model\Order::STATE_PROCESSING,
                    \Magento\Sales\Model\Order::STATE_COMPLETE
                ]]
            )
            ->setOrderStateFilter(\Magento\Sales\Model\Order::STATE_CANCELED, true)
            ->load();
                
        $data = [
            'uid' => $uid,
            'lifetime_sales' => $saleModel->getTotals()->getLifetime()
        ];
        
        
        $this->curlClient->setCredentials($username, $password);
        try {
            $this->curlClient->post($url, $data);
            if ($this->curlClient->getStatus() == 200) {
                return \Zend_Json::encode(['valid' => true, 'message' => 'Refresh stats completed']);
            } else {
                return \Zend_Json::encode(['valid' => false, 'message' => 'Bad credentials']);
            }
        } catch (\Exception $e) {
            if ($this->scopeConfig->getValue('lifetime_sales/config/logging', Store::SCOPE_STORE)) {
                Mage::log('Curl error: '.$e->getMessage(), null, $this->logFile);
            }
            return \Zend_Json::encode(['valid' => false, 'message' => $e->getMessage()]);
        }
    }
}
