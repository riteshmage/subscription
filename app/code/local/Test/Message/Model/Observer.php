<?php
class Test_Message_Model_Observer extends Varien_Event_Observer
{
   public function __construct()
   {
 
   }

   public function saveCmsPageObserve($observer)
   {
      $event = $observer->getEvent();  
      $array = $event->getPage();
      $url   = $array->getData('identifier');
      $id    = $array->getData('page_id');
      $cmspage = Mage::getModel('cms/page')
                  ->load($id);
      $time =Mage::getModel('core/date')->gmtDate('Y-m-d H:i:s');
      
      if($cmspage->getId())
      {  
         $cmspageurl = $cmspage->getData('identifier');
         $model = Mage::getModel('message/cmspage');
         $check = $model->getCollection()->addFieldToFilter('page_url_key',array('eq'=>$url));

         $model->load($cmspageurl, 'page_url_key');
         if(!empty($check->getData()))
         {
            return;
         }
         $data = array('page_url_key'=>$url,'updated_at'=>$time);
         $model->addData($data);
      }
      else
      {
          $model = Mage::getModel('message/cmspage');
          $data=array('page_url_key'=>$url,'updated_at'=>$time);
          $model->addData($data);
      }
      $model->save();

      //add log to events.log
      Mage::log($url, null, 'events.log', true);
   }
}  
