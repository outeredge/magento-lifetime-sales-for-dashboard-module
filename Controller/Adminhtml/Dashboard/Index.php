<?php

namespace OuterEdge\LifetimeSalesForDashboard\Controller\Adminhtml\Dashboard;

class Index extends \Magento\Backend\App\Action
{

    protected $resultJsonFactory;
    
    protected $helper;

    /**
     * @param \Magento\Backend\App\Action\Context              $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \OuterEdge\LifetimeSalesForDashboard\Helper\Data $helper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \OuterEdge\LifetimeSalesForDashboard\Helper\Data $helper
    ) {
        
        $this->resultJsonFactory = $resultJsonFactory;
        $this->helper = $helper;
        
        parent::__construct($context);
    }

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
