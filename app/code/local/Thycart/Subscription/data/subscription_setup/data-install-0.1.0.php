<?php
$units = array(
				array(
					'subscription_unit' => 'weekly',
					'number_of_days'	=>	7,
					'active'			=> 	1
				),
				array(
					'subscription_unit' => 'Monthly',
					'number_of_days'	=>	30,
					'active'			=> 	1
				),
				array(
					'subscription_unit' => 'quaterly',
					'number_of_days'	=>	90,
					'active'			=> 	1
				),
			);
foreach ($units as $unit)
{
	$unitModel = Mage::getModel('subscription/unit');
	$unitModel->addData($unit);
	$unitModel->save();
}