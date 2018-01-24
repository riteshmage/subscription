<?php 
class Test_Message_Model_Resource_Message extends Mage_Core_Model_Resource_Db_Abstract
{
     public function _construct()
     {
         $this->_init('message/message', 'message_id');
     }
}
