<?php

namespace OuterEdge\LifetimeSalesForDashboard\Controller\Adminhtml\Dashboard;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use OuterEdge\LifetimeSalesForDashboard\Helper\Data;

class Index extends Action
{
    /**
     * @var JsonFactory $resultJsonFactory
     */
    protected $resultJsonFactory;
    
    /**
     * @var Data $helper
     */
    protected $helper;

    /**
     * @param Context     $context
     * @param JsonFactory $resultJsonFactory
     * @param Data        $helper
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        Data $helper
    ) {
        
        $this->resultJsonFactory = $resultJsonFactory;
        $this->helper = $helper;
        
        parent::__construct($context);
    }

    /**
     * 
     * @return type
     */
    public function execute() 
    {
        $result = $this->helper->sendLifetimeSales();

        /**
         * @var \Magento\Framework\Controller\Result\Json $resultJson 
         */
        $resultJson = $this->resultJsonFactory->create();
        
        return $resultJson->setData(
            [
            'valid' => (int)$result['valid'],
            'message' => $result['message'],
            ]
        );
    }
}
