<?php
/**
 * @copyright   Copyright(c) 2012
 * @author      jeff zeng(h6k65@126.com)
 * @version     1.0
 */


class Newjueqi_Specialgiftcard_Model_Giftcardaccount extends Enterprise_GiftCardAccount_Model_Giftcardaccount
{
	
	/*
	 * batch to genernal special gift card according to rule
	 * @param   string $type see Newjueqi_Specialgiftcard_Model_Specialgiftcard GIFT_CARD_TYPE_*
	 * @param   int $times genernal how many gift card code for one time
	 * @param   array $param  value add in table "enterprise_giftcardaccount"
     * @return  array: the genernal gift card object
	 */
	public function generateSpecialGiftAccount( $type, $times, $param=array() ){

        $accountObjs=array();
        
        $poolObj=  Mage::getModel('enterprise_giftcardaccount/pool');

        //generate all giftcard codes from code pool
        $giftCardCodes= $poolObj->generateSpecialGiftCardPool( $type ,$times );
        
        for( $i=0;$i<$times;$i++ ){
        	
        	$model = Mage::getModel('enterprise_giftcardaccount/giftcardaccount');	
        	
        	$gcData=array();
        	switch( $type ){
				case Newjueqi_Specialgiftcard_Model_Specialgiftcard::GIFT_CARD_TYPE_REWARD:
					$gcData=$this->getRewrdGiftCardParam( $param );
					break;        		
        	}
        	
            //get one giftcard code 
        	if(isset( $giftCardCodes[$i] ) && $poolObj->checkCodeIsFree( $giftCardCodes[$i] ) ){
        		$gcData['code']= $giftCardCodes[$i] ;
        	}
        	
        	$model->addData($gcData);
        	$model->save();	
        	$accountObjs[]=$model;
        	
        }	

        return $accountObjs;
		
	}

    /**
      * set param of giftcard
      *
      * @param $param
      *
      * @returns   
     */
	protected function getRewrdGiftCardParam( $param=array()  ){
		
		
        $gcData = array(
            'status' => $param['status'],
            'is_redeemable' => $param['is_redeemable'],
            'website_id' => $param['website_id'],
            'balance' => $param['balance'],
        	'date_expires' => $param['date_expires'],
        );
        
        return $gcData;
	}
	
	

    
}
