<?php
namespace OuterEdge\LifetimeSalesForDashboard\Block\System\Config\Form;
 
use Magento\Framework\Data\Form\Element\AbstractElement;

class Button extends \Magento\Config\Block\System\Config\Form\Field
{
     const BUTTON_TEMPLATE = 'system/config/form/button.phtml';
 
     /**
     * Set template to itself
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate(static::BUTTON_TEMPLATE);
        }
        return $this;
    }
    /**
     * Render button
     *
     * @param  AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        // Remove scope label
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }
   
     /**
     * Get the button and scripts contents
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $this->addData(
            [
                'button_label' => ('Push Stats to Server'),
                'html_id' => $element->getHtmlId(),
                'ajax_url' => $this->_urlBuilder
                    ->getUrl('lifetimesalesfordashboard/dashboard/index'),
            ]
        );
        
        return $this->_toHtml();
    }
}