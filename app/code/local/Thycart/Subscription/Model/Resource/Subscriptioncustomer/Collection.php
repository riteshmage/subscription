<?php 
class Thycart_Subscription_Model_Resource_Subscriptioncustomer_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
 {
     public function _construct()
     {
         $this->_init('subscription/subscriptioncustomer');
     }
}
