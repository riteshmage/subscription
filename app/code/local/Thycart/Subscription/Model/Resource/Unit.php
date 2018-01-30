<?php
class Thycart_Subscription_Model_Resource_Unit extends  Mage_Core_Model_Resource_Db_Abstract
{
     public function _construct()
     {
         $this->_init('subscription/unit','unit_id');
     }
}
