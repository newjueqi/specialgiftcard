<?php
/**
 * giftcard pool  
 * @copyright   Copyright(c) 2012
 * @author      jeff zeng(h6k65@126.com)
 * @version     1.0
 */

class Newjueqi_Specialgiftcard_Model_Pool extends Enterprise_GiftCardAccount_Model_Pool
{
    const REWARD_GIFTCARD_XML_CONFIG_CODE_FORMAT = 'giftcard/reward_giftcardaccount_general/code_format';
    const REWARD_GIFTCARD_XML_CONFIG_CODE_LENGTH = 'giftcard/reward_giftcardaccount_general/code_length';
    const REWARD_GIFTCARD_XML_CONFIG_CODE_PREFIX = 'giftcard/reward_giftcardaccount_general/code_prefix';
    const REWARD_GIFTCARD_XML_CONFIG_CODE_SUFFIX = 'giftcard/reward_giftcardaccount_general/code_suffix';
    const REWARD_GIFTCARD_XML_CONFIG_CODE_SPLIT  = 'giftcard/reward_giftcardaccount_general/code_split';
    const REWARD_GIFTCARD_XML_CONFIG_CODE_BALANCE  = 'giftcard/reward_giftcardaccount_general/balance';
    const REWARD_GIFTCARD_XML_CONFIG_CODE_WEBSITE  = 'giftcard/reward_giftcardaccount_general/website';
    const REWARD_GIFTCARD_XML_CONFIG_CODE_EXPIRES_DATE  = 'giftcard/reward_giftcardaccount_general/expires_date';
    const REWARD_GIFTCARD_XML_CONFIG_CODE_GENERATE_TIME  = 'giftcard/reward_giftcardaccount_general/generate_time';

	/*
	 * generate some special gift cards:
	 * @param string $type 
	 * @param int  $number: generate how many gift card for one time 
     * @return array : all generate gift card number
	 */
	public function generateSpecialGiftCardPool(  $type, $number ){
		
		$param=array();
		switch( $type  ){
			case Newjueqi_Specialgiftcard_Model_Specialgiftcard::GIFT_CARD_TYPE_REWARD:
				$param['code_format']=Mage::getStoreConfig(self::REWARD_GIFTCARD_XML_CONFIG_CODE_FORMAT);
				$param['code_length']=Mage::getStoreConfig(self::REWARD_GIFTCARD_XML_CONFIG_CODE_LENGTH);
				$param['code_prefix']=Mage::getStoreConfig(self::REWARD_GIFTCARD_XML_CONFIG_CODE_PREFIX);
				$param['code_suffix']=Mage::getStoreConfig(self::REWARD_GIFTCARD_XML_CONFIG_CODE_SUFFIX);
				$param['code_split']=Mage::getStoreConfig(self::REWARD_GIFTCARD_XML_CONFIG_CODE_SPLIT);					
				break;
		}
		
		return $this->_generateSpecialGiftCardPool( $param,$number );
		
	}
	
    /**
      * generate some special gift cards: 
      *
      * @param $param
      * @param $number how many giftcard
      *
      * @returns   
     */
	protected function _generateSpecialGiftCardPool( $param=array(), $number ){

        $website = Mage::app()->getWebsite($this->getWebsiteId());
        $size = $number;

        $codes=array();
        for ($i=0; $i<$size;$i++) {
            $attempt = 0;
            do {
                if ($attempt>=self::CODE_GENERATION_ATTEMPTS) {
                    Mage::throwException(Mage::helper('enterprise_giftcardaccount')->__('Unable to create full code pool size. Please check settings and try again.'));
                }
                $code = $this->_generateSpecialGiftCardCode($param);
                $attempt++;
            } while ($this->getResource()->exists($code));

            $this->getResource()->saveCode($code);
            $codes[]=$code;
        }
        return $codes;
		
	}
	
    /**
      * generate some special gift cards: 
      *
      * @param $param
      *
      * @returns  codestring 
     */
	protected function _generateSpecialGiftCardCode( $param=array() ){
        $website = Mage::app()->getWebsite($this->getWebsiteId());

        if( isset($param['code_format'])  ){
        	$format  = $param['code_format'];
        }else{
        	$format  = $website->getConfig(self::XML_CONFIG_CODE_FORMAT);
        }
        
        if( isset($param['code_length'])  ){
        	$length  = $param['code_length'];
        }else{
        	$length  = max(1, (int) $website->getConfig(self::XML_CONFIG_CODE_LENGTH));
        }
        
        if( isset($param['code_prefix'])  ){
        	$prefix  = $param['code_prefix'];
        }else{
        	$prefix  = $website->getConfig(self::XML_CONFIG_CODE_PREFIX);
        }
        
        if( isset($param['code_suffix'])  ){
        	$suffix   = $param['code_suffix'];
        }else{
        	$suffix   = $website->getConfig(self::XML_CONFIG_CODE_SUFFIX);
        }
        
        if( isset($param['code_split'])  ){
        	$split  = $param['code_split'];
        }else{
        	$split  =  max(0, (int) $website->getConfig(self::XML_CONFIG_CODE_SPLIT));
        }
        
	    if (!$format) {
            $format = 'alphanum';
        }        
        $splitChar = $this->getCodeSeparator();
        $charset = str_split((string) Mage::app()->getConfig()->getNode(sprintf(self::XML_CHARSET_NODE, $format)));

        $code = '';
        for ($i=0; $i<$length; $i++) {
            $char = $charset[array_rand($charset)];
            if ($split > 0 && ($i%$split) == 0 && $i != 0) {
                $char = "{$splitChar}{$char}";
            }
            $code .= $char;
        }

        $code = "{$prefix}{$code}{$suffix}";
        return $code;		
	}
	

    /**
      *
	  * check database: this code is exist and status is STATUS_FREE="0"
      * @param $code
      *
      * @returns boolean   
     */
	public function checkCodeIsFree( $code ){
		 
	    $dbr = Mage::getSingleton ( 'core/resource' )->getConnection ( 'core_read' );
		$sql = "select * from enterprise_giftcardaccount_pool where code='{$code}' and status=0" ;
		$result = $dbr->fetchOne($sql);
		if( $result ){
			return true;
		}
		return false;
		
	}
	
}
