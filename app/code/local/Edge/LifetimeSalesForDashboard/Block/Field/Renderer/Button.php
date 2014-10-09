<?php

class Edge_LifetimeSalesForDashboard_Block_Field_Renderer_Button extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        $url = $this->getUrl('lifetimesales/force');

        return $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setType('button')
            ->setClass('scalable')
            ->setLabel($element->getLabel())
            ->setOnClick("setLocation('$url')")
            ->toHtml();
    }
}