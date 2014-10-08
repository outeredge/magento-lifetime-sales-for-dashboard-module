<?php

class Edge_LifetimeSalesForDashboard_Block_Field_Renderer_Disabled extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $element->setDisabled('disabled');
        return parent::_getElementHtml($element);
    }
}