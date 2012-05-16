<?php
/**
 * main controller for generate giftcard
 * @copyright   Copyright(c) 2012
 * @author      jeff zeng(h6k65@126.com)
 * @version     1.0
 */

class Newjueqi_Specialgiftcard_Adminhtml_SpecialgiftcardController extends Mage_Adminhtml_Controller_Action
{
 
	
  /**
    * batch generate giftcard
    *
    * @returns   
   */
    public function generateRewardGiftcardAction(){
    	
        try {
        	
            //get config from BO
	        $type=Newjueqi_Specialgiftcard_Model_Specialgiftcard::GIFT_CARD_TYPE_REWARD;
	    	$times= Mage::getStoreConfig(Newjueqi_Specialgiftcard_Model_Pool::REWARD_GIFTCARD_XML_CONFIG_CODE_GENERATE_TIME); ;
	    	$balance= Mage::getStoreConfig(Newjueqi_Specialgiftcard_Model_Pool::REWARD_GIFTCARD_XML_CONFIG_CODE_BALANCE); ;
	    	$website= Mage::getStoreConfig(Newjueqi_Specialgiftcard_Model_Pool::REWARD_GIFTCARD_XML_CONFIG_CODE_WEBSITE  ); ;
	    	$dateExpires= Mage::getStoreConfig(Newjueqi_Specialgiftcard_Model_Pool::REWARD_GIFTCARD_XML_CONFIG_CODE_EXPIRES_DATE); ;

            //if some config are not set, use default value
	    	if( !is_numeric($times) ){
	    		$times=3;
	    	}
	    	
        	if( !is_numeric($balance) ){
	    		$balance=100;
	    	}
	    	
        	if( !is_numeric($website) ){
              //set id of the first website 
              $website=Mage::getSingleton('core/website')
                ->getCollection()->getFirstItem()->getId();
	    	}

            if (!Zend_Validate::is( $dateExpires , 'Date')) {
                Mage::getSingleton('adminhtml/session')->addError( 
                	Mage::helper('specialgiftcard')->__('date format error') );
                $this->_redirectReferer('*/*/');
                return;
            }
	    	
	    	$param = array(
	            'status' => 1,
	            'is_redeemable' => 1,
	            'website_id' => $website,
	            'balance' => $balance,
	        	'date_expires' => $dateExpires,
	        );
	    	$codes= Mage::getModel('enterprise_giftcardaccount/giftcardaccount')
    	 		->generateSpecialGiftAccount( $type, $times, $param );
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('enterprise_giftcardaccount')->__('gift card generate successful'));
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addException($e, Mage::helper('enterprise_giftcardaccount')->__('Unable to generate new code pool.'));
        }
        $this->_redirectReferer('*/*/');
        
    }

    
    
    /**
      * format date to internal format  
      *
      * @param $date
      *
      * @returns date   
     */
    private function _formatDate( $date ){
        $filterInput = new Zend_Filter_LocalizedToNormalized(array(
            'date_format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT)
        ));
        $filterInternal = new Zend_Filter_NormalizedToLocalized(array(
            'date_format' => Varien_Date::DATE_INTERNAL_FORMAT
        ));
         $date = $filterInput->filter($date);
        $date = $filterInternal->filter($date);    	
        return $date;
    }
	
}
