<?php 
class Test_Message_Model_Resource_Cmspage_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
 {
     public function _construct()
     {
         $this->_init('message/cmspage');
     }
}
