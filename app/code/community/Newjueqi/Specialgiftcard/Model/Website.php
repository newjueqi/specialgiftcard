<?php
/**
 * generate website select option 
 * @copyright   Copyright(c) 2012
 * @author      jeff zeng(h6k65@126.com)
 * @version     1.0
 */

class Newjueqi_Specialgiftcard_Model_Website {
    /**
     * Return list of gift card account code formats as options array.
     * If $addEmpty true - add empty option
     *
     * @param boolean $addEmpty
     * @return array
     */
    public function toOptionArray($addEmpty = false)
    {
      return Mage::getSingleton('adminhtml/system_store')->getWebsiteValuesForForm(false,false); 
    }

	 
}
