<?php 
class Test_Message_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function filteration($var)
	{
		$data = trim($var);
		$data = htmlspecialchars($data);
		return $data;
	}
	public function test()
	{
		echo "test helper";
	}
}
