<?php
class Newjueqi_Specialgiftcard_Block_Adminhtml_System_Config_Generate extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->getTemplate()) {
            $this->setTemplate('specialgiftcard/config/generate.phtml');
        }
    }

    /**
     * Get the button and scripts contents
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);
        return $this->_toHtml();
    }


}
