<?php
require_once('phpmailer/class.phpmailer.php');
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
	public function isDigit($var, $length=0)
	{
		$pattern = "/^\d+$/";
		if(preg_match($pattern, $var))
		{
			if($length && strlen($length) != $length)
			{
				return false;	
			}
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

	public function validateData($postData)
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

	public function isEmail($email)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL))
		{
  			return true;
		}
		return false;
	}
	public function isAlpha($var)
	{
		if (preg_match("/^[a-zA-Z ]*$/",$var))
		{
  			return true;
		}
		return false;
	}
	public function sendMail($to, $recepientName, $subject, $status, $productName ,$unit)
    {
        if(empty($to) || empty($recepientName) || empty($subject) ||empty($status) || empty($productName) || empty($unit))
        {
            return false;
        }
        if(!$this->isEmail($to) || !$this->isAlpha($recepientName) || !$this->isAlpha($status))
        {
        	return false;
        }
        if(PHP_MAILER)
        { 
            try
            {
                $mail = new PHPMailer();
                $mail->IsSMTP();
                $mail->SMTPDebug = 1; 
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = 'ssl';
                $mail->Host = "smtp.gmail.com";
                $mail->Port = 465;
                $mail->IsHTML(true);
                $mail->Username = Mage::getStoreConfig('notification/email_group/sender_email');
                $mail->Password = '';
                $mail->SetFrom(Mage::getStoreConfig('notification/email_group/sender_email'));
                $mail->Subject = $subject;
                $mail->Body = Mage::helper('subscription')->getEmailBody($status, $recepientName, $productName ,$unit);
                $mail->AddAddress($to);
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
                return false;
            }
            
            if(!$mail->Send()) 
            {
               return false;
            }   
        }
        if(MAGENTO_MODEL)
        {
            $mail = Mage::getModel('core/email');
            $mail->setToName($recepientName);
            $mail->setToEmail($to);
            $mail->setBody(Mage::helper('subscription')->getEmailBody($status, $recepientName, $productName ,$unit));
            $mail->setSubject($subject);
            $mail->setFromEmail(Mage::getStoreConfig('notification/email_group/sender_email'));
            $mail->setType('html');
            try
            {
                if(!$mail->send())
                {
                    return false;
                }
            }
            catch (Exception $e)
            {
                Mage::getSingleton('core/session')->addError('Unable to send  Email.');
                return false;
            }   
        }
        if(ZEND_FUNCTION)
        {
            $sender_email = Mage::getStoreConfig('notification/email_group/sender_email');
            $sender_name  = Mage::getStoreConfig('notification/email_group/sender_name');

            $mail = new Zend_Mail();
            $mail->setBodyHtml(Mage::helper('subscription')->getEmailBody($status, $recepientName, $productName ,$unit)); 
            $mail->setFrom($sender_email,$sender_name);
            $mail->addTo($to, 'customer');
            //$mail->addCc($cc, $ccname);    //can set cc
            //$mail->addBCc($bcc, $bccname);    //can set bcc
            $mail->setSubject($subject);
            try
            {
                if(!$mail->send())
                {
                    return false;
                }
            }
            catch(Exception $ex)
            {
                echo $e->getMessage();
                return false;
            }
        }
        if(Mage::getStoreConfig('notification/email_group/email'))
        {
            $templateId     = 2;     
            $senderName     = Mage::getStoreConfig('notification/email_group/sender_name');
            $senderEmail    = Mage::getStoreConfig('notification/email_group/sender_email');        
            $sender         = array('name' => $senderName,
                                    'email' => $senderEmail
                                   );              
            $storeId = Mage::app()->getStore()->getId();
            $vars    = array('status' => $status,
                             'customerName'=> $recepientName,
                             'productName'=>$productName,
                             'unit'=>$unit
                            );
            try
            {
                $translate  = Mage::getSingleton('core/translate');
                Mage::getModel('core/email_template')
                        ->sendTransactional($templateId, $sender, $to, $recepientName, $vars, $storeId);    
                $translate->setTranslateInline(true);   
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
                return false;
            }
        }     
        return true;
    }

    public function getEmailBody($status, $recepientName, $productName ,$unit)
    { 
        $body ='';
        $emailTemplateVars = array();
        if(empty($status) || empty($productName))
        {
            return false;
        }
       
        $emailTemplateVars['customerName'] = $recepientName;
        $emailTemplateVars['productName']  = $productName;
        $emailTemplateVars['status'] 	   = $status;
        $emailTemplateVars['unit']		   = $unit;
        try
        {
            $emailTemplate  = Mage::getModel('core/email_template')
                            ->loadDefault('subscription_template');
            $body = $emailTemplate->getProcessedTemplate($emailTemplateVars);
        }
        catch(Exception $e)
        {
           echo $e->getMessage(); 
           return;
        }
       return $body;
    }	
}