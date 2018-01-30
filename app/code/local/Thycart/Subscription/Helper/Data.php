<?php
class Thycart_Subscription_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getUnitMaster()
	{
		$unit = Mage::getModel('subscription/unit')->getCollection()
		->addFieldToSelect(array('unit_id','subscription_unit'))
		->addFieldToFilter('active',1)->getData();
		$units = [];
		foreach ($unit as $key=>$value)
		{
			$units[$key]['value'] = $value['unit_id']; 
			$units[$key]['label'] = ucwords($value['subscription_unit']);
		}
		// echo "<pre>";print_r($units);die;
		$finalArr['value'] = $units;
		$finalArr['label'] = 'Please Select Unit';
		return $finalArr;

	}
}

// 'values' => array(

// 	'1' => array(
// 		'value'=> array( ),
// 		'label' => 'Size'    
// 	),                                        

// )