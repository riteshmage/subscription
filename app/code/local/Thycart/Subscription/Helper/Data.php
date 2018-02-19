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
		$finalArr['value'] = $units;
		$finalArr['label'] = 'Please Select Unit';
		return $finalArr;
	}

	public function isNumber($var)
	{
		if(is_numeric($var))
		{
			return true;
		}
		return false;
	}

	public function isAlphanum($var)
	{
		$validator = new Zend_Validate_Alnum(array('allowWhiteSpace' => true));
		if($validator->isValid($var))
		{
			return true;
		}
		return false;
	}

	public function numericRange($num, $min, $max)
	{
		$validator  = new Zend_Validate_Between(array('min' => $min, 'max' => $max));
		if($validator->isValid($num))
		{
			return true;
		}
		return false;
	}

	public function validateData($postData=array())
	{
		foreach ($postData as $key => $value)
		{
			if(is_array($value))
			{
				continue;
			}
			$data = trim($value);
			$data = Mage::helper('core')->escapeHtml($data);
			$postData[$key] =$data;
		}
		return $postData;
	}
}