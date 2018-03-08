<?php
class Test_Message_IndexController extends Mage_Core_Controller_Front_Action
{
    public function encrypt($action, $string)
    {
        $output = false;
     
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'hsbvsdj37hs7123duedjsh890B374y6n21AA024E4FADD5B497FDFF1A8EA6FF12F6FB65AF2720B59CCF';
        $secret_iv = 'kjhsadwakshuisuh97E89275A52C59A388306B13C3BD8njky7h';
    
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
     
        if( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        }
        else if( $action == 'decrypt' ){
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
     
        return $output;
    }
    public function indexAction()
    {
        // var_dump(__METHOD__);
        // $block = new Mage_Core_Block_Text();
        // $block->setText("Helloo World");
        // echo $block->toHtml();

        // $block =  new Mage_Core_Block_Template();
        // $block->setTemplate('hello.phtml');
        // var_dump($block->getTemplateFile());
        // echo $block->toHtml();

        $block1 =  new Mage_Core_Block_Text();
        $block1->setText('Child ');

        // $block2 = new Mage_Core_Block_Template();
        // $block2->setTemplate('hello.phtml');
        // $block2->setChild('child',$block);
        // echo $block2->toHtml();

        $layout = Mage::getSingleton('core/layout');
        $block  = $layout->createBlock('core/template','root');
        $block->setChild('child',$block1);
        $block->setTemplate('hello.phtml');
        echo $block->toHtml();

        // echo $output = $this->encrypt('encrypt','ritesh'); 
        // echo '<br>'.$this->encrypt('decrypt',$output);


        // $emailTemplate  = Mage::getModel('core/email_template')
        // ->loadDefault('subscription_template');

        // $emailTemplateVars = array();
        // $emailTemplateVars['status'] = 'Subscribed';

        // $emailTemplateVars['customerName'] = 'Ritesh';
        // $emailTemplateVars['productName']  = 'Ayurvedic chai';
        // echo $emailTemplate->getProcessedTemplate($emailTemplateVars);   

        // Mage::helper('subscription')->sendMail('ritesh.shukla@adapty.com','Ritesh','testing','Subscribed','AYURVEDIC CHAI');
        //         $customer = Mage::getSingleton('customer/session')->getCustomer();
        // echo "<pre>";
        // echo $customer->getName();
        // $attribute_value = Mage::getResourceModel('catalog/product')->getAttributeRawValue(7, 'name',1);
        // echo ($attribute_value);
        // $test = 
        // "<script> alert('sdfsdfs'); </script>";
        // //echo $this->htmlEscape($test);
        // echo Mage::helper('core')->escapeHtml("$test");

        // echo Mage::app()->getStore()->getStoreId();

        // echo Mage::helper('core/url')->getCurrentUrl();

        // $base_path = Mage::getBaseDir('base');
        // var_dump($base_path);

        // $etc_path = Mage::getBaseDir('design');
        // var_dump($etc_path);

        // echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);

        // echo Mage::getUrl('',array('_forced_secure'=>true));

        // $countryList = Mage::getResourceModel('directory/country_collection')
        // ->loadData()
        // ->toOptionArray(false);
        echo '<pre>';
        // print_r( $countryList);
        // exit('</pre>');

        // $regionCollection = Mage::getModel('directory/region')
        // ->getCollection()
        // ->addCountryFilter('us');
        // $regions = $regionCollection->toOptionArray();
        // print_r($regionCollection->getData());

        // print_r( Mage::app()->getStores());

        // $product = Mage::getModel('catalog/product')->loadByAttribute('sku', 'test');
        // $categories = $product->getCategoryCollection();
        // print_r($categories);

        // $category = Mage::getModel('catalog/category')->load(5);
        // $products = $category->getProductCollection();
        // print_r($products->getData());

        // $product = Mage::getModel('catalog/product')->load(4);
        // $qtyStock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getQty();
        // print_r($qtyStock);

        // $customerOrderCollection = Mage::getModel('sales/order')->getCollection()->addFieldToFilter('customer_id', 1);
        // print_r($customerOrderCollection->getData());

        // $order =Mage::getModel('sales/order')->load(115);
        // print_r($order->getStatusLabel());
        // $invoice = Mage::getModel('sales/order_invoice')->load();

        // $model = Mage::getModel('sales/order_shipment');
        // $tracks = $model->load(1)->getAllTracks();
        // $collection = $model->getCollection();
        // $data = $collection->getData();
        // // print_r($data);
        // foreach ($tracks as $track) {
        //     echo $track->getNumber();
        // }

        // $order = Mage::getModel('sales/order')->load(1);
        // foreach($order->getShipmentsCollection() as $shipment)
        // {
        //     print_r($shipment->getData()); //get each shipment data here...
        // }
        // $resources = Mage::getModel('admin/roles')->getResourcesTree();
        // $nodes = $resources->xpath('//*[@aclpath]');
        // echo '<dl>';
        // foreach($nodes as $node){
        //     echo '<dt>' . (string)$node->title . '</dt>';
        //     echo '<dd>' . $node->getAttribute('aclpath') . '</dd>';
        // }
        // echo '</dl>';
        // var_dump(Mage::getSingleton('admin/session')->isAllowed('admin/sales/order/actions/scforce'));

        // echo Mage::helper("adminhtml")->getUrl("subscription/adminhtml_index/index/");

        // $r = Mage::getSingleton('core/resource')->getConnection('core_read');
        // $tableName = $r->getTable('catalog/product');
        // echo $tableName;

        // $w = Mage::getSingleton('core/resource')->getConnection('core_write');
        // $result = $w->query("select * from unit_master");
        //     if (!$result) {
        //         return false;
        //     }
        //     foreach ($result as $value) {

        //         // $row = $value->fetch(PDO::FETCH_ASSOC);
        //     print_r($value);
        //     }
        //     // if (!$row) {
        //     //     return false;
        //     // }


        // echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'<br>';
        // echo Mage::helper('core/url')->getCurrentUrl();;
        // $_category = Mage::getModel('catalog/layer')->getCurrentCategory();
        // print_r($_category->getData());
            // print_r( Mage::app()->getCache());
        // print_r( Mage::getModel('core/session')->getVisitorId());
    }
    
    public function testAction()
    {
        $this->loadLayout();
            //header('Content-Type: text-xml');
            //die($this->getLayout()->getNode()->asXml());
        $this->renderLayout();
            // Zend_Debug::dump($this->getLayout()->getUpdate()->getHandles());
    }
    public function saveAction()
    {
        if(!$this->getRequest()->isPost())
        { 
            Mage::getSingleton('core/session')->addError('Data not posted');
            $this->_redirect('message/index/test'); 
        }
        $name = $this->getRequest()->getPost('message_name');
        $description = $this->getRequest()->getParam('message_description');
        $data = array('message_name' => $name,'message_description' => $description);
        if($name == '')
        {
            echo "Name is empty";
        } 
        elseif($name!='' && $description!='')
        {
            $contact = Mage::getModel('message/message');
            $contact->addData($data);
            $contact->save();
            Mage::getSingleton('core/session')->addSuccess('Entered Sucessfully');
            $this->_redirect('message/index/test');
        }
        else
        {
            Mage::getSingleton('core/session')->addError('Data not entered');
            $this->_redirect('message/index/test');
        }
    }

    public function sendEmailAction()
    {
        require_once('PHPMailer/class.phpmailer.php');
            $mail = new PHPMailer(); // create a new object
            $mail->IsSMTP(); // enable SMTP
            $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
            $mail->SMTPAuth = true; // authentication enabled
            $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 465; // or 587
            $mail->IsHTML(true);
            $mail->Username = "ritesh.shukla@adapty.com";
            $mail->Password = "shukla0912";
            $mail->SetFrom("asdkjhsdjkhjds.shukla@adapty.com");
            $mail->Subject = "Test";
            $mail->Body = "hello";
            $mail->AddAddress("rajendra.namdeo@adapty.com");

            if(!$mail->Send())
            {
                echo "Mailer Error: " . $mail->ErrorInfo;
            }
            else
            {
                echo "Message has been sent";
            }
            // $fromEmail = "ritesh.shuklaa@adapty.com"; // sender email address
            // $fromName = "John Doe"; // sender name
            
            // $toEmail = "rajendra.namdeo@adapty.com"; // recipient email address
            // $toName = "Mark Doe"; // recipient name
            
            // $body = "This is Test Email!"; // body text
            // $subject = "Test Subject"; // subject text
            
            // $mail = new Zend_Mail();        
            
            // $mail->setBodyText($body);
            
            // $mail->setFrom($fromEmail, $fromName);
            
            // $mail->addTo($toEmail, $toName);
            
            // $mail->setSubject($subject);

            // try {
            //     if($mail->send())
            //         echo "done";
            //     else
            //         echo "not done";
            // }
            // catch(Exception $ex) {
            //     // I assume you have your custom module. 
            //     // If not, you may keep 'customer' instead of 'yourmodule'.
            //     print_r($ex->getMessage());
            // }
    }  
}