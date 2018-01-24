<?php
class Test_Message_Model_Api2_Message_Rest_Guest_V1 extends Test_Message_Model_Api2_Message_Rest
{
    protected function _create(array $data)
    {
        $json =array('status'=>0,'msg'=>'no data found');
        if(empty($data))
        {
            $json['error'] = 'No data';
        }
        if(isset($data['message_name']) && !empty($data['message_name']))
        {   
            $fname = $data['message_name'];
            $name  = Mage::helper('message')->filteration($fname);             
        }
        if (isset($data['message_description']) && !empty($data['message_name']))
        {   
            $fdescription = $data['message_description'];
            $description  = Mage::helper('message')->filteration($fdescription); 
        }
        if(!empty($data))
        { 
            $data = array('message_name' => $name,'message_description' => $description);
            try
            {
                $model = Mage::getModel('message/message');
                $model->addData($data);
                $model->save();
                $json['status'] = 1;
                $json['msg'] = 'Inserted sussecfully';
            }
            catch (Exception $e)
            {
                $json['error'] = 'data not inserted';
            }
        }
        $this->getResponse()->clearHeaders()->setHeader('Content-type', 'application/json', true);
        $this->getResponse()->setBody(json_encode(array('status' => $json)));   
    }
    protected function _update(array $data)
    {
        $this->_critical(self::RESOURCE_METHOD_NOT_ALLOWED);
    }
    protected function _retrieveCollection()
    {
        try
        {
            $model = Mage::getModel('message/message')->getCollection();
            $message_data = $model->getData();   
        }
        catch (Exception $e)
        {
            $message_data['error'] = 'No Message';        
        }
        return $message_data;
    }
    protected function _retrieve()
    {
    	$message_data = array();
    	if($this->getRequest()->getParam('message_name'))
    	{
    		$name = $this->getRequest()->getParam('message_name');
            $message_name = Mage::helper('message')->filteration($name);
            try
            {
                $model = Mage::getModel('message/message')->load($message_name ,'message_name');
                if($model->getId())
                {
                    $message_data['message_name'] = $model->getMessageName();
                    $message_data['message_description'] = $model->getMessageDescription();
                }
                else
                {
                    $message_data['error'] = 'No such message in database';
                }              
            }
            catch (Exception $e)
            {
                $message_data['error'] = 'Exception occured ='.$e;             
            }	
    	}
    	else
    	{
    		$message_data['error'] = 'No Message';
    	}
    	return $message_data;
    }
}