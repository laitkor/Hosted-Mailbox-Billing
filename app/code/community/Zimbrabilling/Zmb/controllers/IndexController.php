<?php
class Zimbrabilling_Zmb_IndexController extends Mage_Core_Controller_Front_Action
{
      /**
     * Pre dispatch action that allows to redirect to no route page in case of disabled extension through admin panel
     */
    public function preDispatch()
    {
        parent::preDispatch();
        
        if (!Mage::helper('zimbrabilling_zmb')->isEnabled()) {
            $this->setFlag('', 'no-dispatch', true);
            $this->_redirect('noRoute');
        }        
    }
    
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->loadLayout();

        $listBlock = $this->getLayout()->getBlock('zmb.list');

        if ($listBlock) {
            $currentPage = abs(intval($this->getRequest()->getParam('p')));
            if ($currentPage < 1) {
                $currentPage = 1;
            }
            $listBlock->setCurrentPage($currentPage);
        }

        $this->renderLayout();
    }

    /**
     * News view action
     */
    public function viewAction()
    {
        $newsId = $this->getRequest()->getParam('id');
        if (!$newsId) {
            return $this->_forward('noRoute');
        }

        /** @var $model Magentostudy_News_Model_News */
        $model = Mage::getModel('zimbrabilling_zmb/zmb');
        $model->load($newsId);

        if (!$model->getId()) {
            return $this->_forward('noRoute');
        }

        Mage::register('zmb_item', $model);
        
        Mage::dispatchEvent('before_zmb_item_display', array('zmb_item' => $model));

        $this->loadLayout();
        $itemBlock = $this->getLayout()->getBlock('zmb.item');
        if ($itemBlock) {
            $listBlock = $this->getLayout()->getBlock('zmb.list');
            if ($listBlock) {
                $page = (int)$listBlock->getCurrentPage() ? (int)$listBlock->getCurrentPage() : 1;
            } else {
                $page = 1;
            }
            $itemBlock->setPage($page);
        }
        $this->renderLayout();
    }
	
	public function checkavailAction(){
		
		$fulldomain = $_POST['fulldomain'];
		$read = Mage::getSingleton('core/resource')->getConnection('core_read');
		$getdata = $read->query("SELECT * FROM zimbrabilling_domains WHERE domain = '".$fulldomain."'")->fetchAll();
		//print_r($getdata);
		//die;
		if(empty($getdata) || count($getdata) == 0){
			$ch = curl_init('http://api.namecheap.com/xml.response?ApiUser=Lambodarinc&ApiKey=fd69475325d2468abf09ee6b15c7be8b&UserName=Lambodarinc&Command=namecheap.domains.check&ClientIp=203.190.146.202&DomainList='.$_POST['domain'].'' );
			#$ch = curl_init('http://yahoo.com');
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
			$result = curl_exec( $ch );
			curl_close( $ch );
			echo $result;
		}else{
			echo 'true';
		}		
	}
	
	public function postAction(){
		$session = Mage::getModel('core/session');
		#print_r($this->getRequest()->getPost());die;
		 if($this->getRequest()->getPost('title')){			
			$createdtime = $this->getRequest()->getPost('created_time');
			$planname = $this->getRequest()->getPost('title');
			$planid = $this->getRequest()->getPost('planid');
			$mailbox = $this->getRequest()->getPost('mailboxes');
			$mailboxsize = $this->getRequest()->getPost('mailboxsize');
			$billingcycle = $this->getRequest()->getPost('billingcycle');
			
			$read = Mage::getSingleton('core/resource')->getConnection('core_read');
			$cycledetail = $read->query("SELECT * FROM `zimbrabilling_cycles` WHERE id = '".$billingcycle."'")->fetchObject();
			
			$session->setData('planname',$planname);
			$session->setData('planid',$planid);
			$session->setData('billingcycle',$billingcycle);
			$session->setData('createdtime',$createdtime);
			$session->setData('mailboxes',$mailbox);
			$session->setData('mailboxsize',$mailboxsize);
			$session->setData('cycleprice',$cycledetail->price);
			$session->setData('cycleduration',$cycledetail->duration);
			$session->setData('renewpermailbox',$cycledetail->per_mailbox_price);
			
			if($this->getRequest()->getPost('merge') && $this->getRequest()->getPost('merge') == 'degrade'){
				$degradecustomerid = $this->getRequest()->getPost('renewcustid');
				$degradedomain = $this->getRequest()->getPost('renewdomain');
				$sql = $read->query("SELECT * FROM `zimbrabilling_domains` WHERE customerid = '".$degradecustomerid."' AND domain = '".$degradedomain."'")->fetchObject();
				#if($sql->planid == $this->getRequest()->getPost('planid')){
					$additional = $sql->additional_mailboxes;
					$renewupgradeprice = $sql->upgrade_price;
					$session->setData('renewadditional',$additional);
					$session->setData('renewupgradeprice',$renewupgradeprice);
				#}
			}
			
			if($this->getRequest()->getPost('planchanged')){
				$read = Mage::getSingleton('core/resource')->getConnection('core_read');
				$renewdomain = $this->getRequest()->getPost('renewdomain');
				#$r = addslashes($renewdomain);
				$renewcustid = $this->getRequest()->getPost('renewcustid');
				$merge = $this->getRequest()->getPost('merge');
				$renewcycle = $this->getRequest()->getPost('billingcycle');
				$renewdata = $read->query("SELECT * FROM `zimbrabilling_domains` WHERE domain = '".$renewdomain."'")->fetchObject();
				
				$session->setData('renewaddmailboxes',$renewdata->additional_mailboxes);
				$session->setData('renewdomain',$renewdomain);
				$session->setData('renewcustid',$renewcustid);
				$session->setData('renewcycle',$renewcycle);
				#echo $renewdomain; die;
				$this->_redirect("zmb?step=3&plan=renew&merge=".$merge);
			}else{
				$this->_redirect('zmb?step=2');
			}
			
		}
		
		if($this->getRequest()->getPost('domain')){
			$domain = $this->getRequest()->getPost('domain');
			$email_prefered = $this->getRequest()->getPost('email_prefered');
			$session->setData('domain',$domain);
			$session->setData('email_prefered',$email_prefered);
		
			
			$mysession = Mage::getModel('core/session')->getData();
			$read = Mage::getSingleton('core/resource')->getConnection('core_read');
			$newdetail = $read->query("SELECT * FROM `zimbrabilling_cycles` WHERE id = '".$mysession['billingcycle']."'")->fetchObject();	

			if($newdetail->duration == 'monthly'){
			    $todayDate = date("Y-m-d");
				$dateOneMonthAdded = strtotime(date("Y-m-d", strtotime($todayDate)) . "+1 month");
			}
			if($newdetail->duration == 'anually'){
			    $todayDate = date("Y-m-d");
				$dateOneMonthAdded = strtotime(date("Y-m-d", strtotime($todayDate)) . "+12 month");
			}
			if($newdetail->duration == 'semi-anually'){
				$todayDate = date("Y-m-d");
				$dateOneMonthAdded = strtotime(date("Y-m-d", strtotime($todayDate)) . "+6 month");
			}
			
			if($newdetail->duration == 'quaterly'){
				$todayDate = date("Y-m-d");
				$dateOneMonthAdded = strtotime(date("Y-m-d", strtotime($todayDate)) . "+4 month");
			}
			
			$session->setData('price',$newdetail->price);
/*			
			$write = Mage::getSingleton('core/resource')->getConnection('core_write');
			$fields = array();
			$fields['plan_title']= $mysession['planname'];
			$fields['billingcycle'] = $mysession['billingcycle'];
			$fields['domain'] = strtolower($mysession['domain']);
			$fields['customerid'] = $mysession['customerid'];
			$fields['planid'] = $mysession['planid'];
			$fields['created_time'] = $mysession['createdtime'];
			$fields['mailboxes'] = $mysession['mailboxes'];
			$fields['email_prefered'] = $mysession['email_prefered'];
			$fields['duration'] = $newdetail->duration;
			$fields['plan_price'] = $newdetail->price;
			$fields['expiry_date'] = date('Y-m-d',$dateOneMonthAdded);*/
			
			#echo '<pre>'; print_r($fields); die;
			
			#$write->insert('zimbrabilling_domains', $fields);
			#$write->commit();
			
			$this->_redirect('zmb?step=3');
		}
		
		if($this->getRequest()->getPost('getalldetails')){ 
		    $customerid = $this->getRequest()->getPost('customerid');
			$session->setData('customerid',$customerid);
		    #echo 'test';
			$mysession = Mage::getModel('core/session')->getData();
			#echo '<pre>';print_r($mysession); die;
			
			$read = Mage::getSingleton('core/resource')->getConnection('core_read');
			$newdetail = $read->query("SELECT * FROM `zimbrabilling_cycles` WHERE id = '".$mysession['billingcycle']."'")->fetchObject();	

			if($newdetail->duration == 'monthly'){
			    $todayDate = date("Y-m-d");
				$dateOneMonthAdded = strtotime(date("Y-m-d", strtotime($todayDate)) . "+1 month");
			}
			if($newdetail->duration == 'anually'){
			    $todayDate = date("Y-m-d");
				$dateOneMonthAdded = strtotime(date("Y-m-d", strtotime($todayDate)) . "+12 month");
			}
			if($newdetail->duration == 'semi-anually'){
				$todayDate = date("Y-m-d");
				$dateOneMonthAdded = strtotime(date("Y-m-d", strtotime($todayDate)) . "+6 month");
			}
			
			if($newdetail->duration == 'quaterly'){
				$todayDate = date("Y-m-d");
				$dateOneMonthAdded = strtotime(date("Y-m-d", strtotime($todayDate)) . "+4 month");
			}
			
			$session->setData('price',$newdetail->price);
			
			$write = Mage::getSingleton('core/resource')->getConnection('core_write');
			$fields = array();
			$fields['plan_title']= $mysession['planname'];
			$fields['billingcycle'] = $mysession['billingcycle'];
			$fields['domain'] = strtolower($mysession['domain']);
			#$fields['customerid'] = $mysession['customerid'];
			$fields['planid'] = $mysession['planid'];
			$fields['created_time'] = $mysession['createdtime'];
			$fields['mailboxes'] = $mysession['mailboxes'];
			$fields['email_prefered'] = $mysession['email_prefered'];
			$fields['duration'] = $newdetail->duration;
			$fields['plan_price'] = $newdetail->price;
			$fields['expiry_date'] = date('Y-m-d',$dateOneMonthAdded);
			$fields['customerid'] = $mysession['customerid'];
			#echo '<pre>'; print_r($fields); die;
			$write->insert('zimbrabilling_domains', $fields);
			$write->commit();
			
			$this->_redirect('zmb?step=4');
		}
		
		if($this->getRequest()->getPost('alreadyselectedplan')){
			 // when renew plan
			  $selectedplan	= $this->getRequest()->getPost('alreadyselectedplan');
			  $customerid = $this->getRequest()->getPost('customerid');	
			  $expirydate = $this->getRequest()->getPost('expirydate');
			  $detail = $this->getRequest()->getPost('detail');
			  $domain = $this->getRequest()->getPost('alreadyselecteddomain');
			  #echo $detail;
			  if($detail == 'monthly'){
				 
				  $dateOneMonthAdded = strtotime(date("Y-m-d", strtotime($expirydate)) . "+1 month");
			  }
			  if($detail == 'anually'){
				
				  $dateOneMonthAdded = strtotime(date("Y-m-d", strtotime($expirydate)) . "+12 month");
			  }
			  if($detail == 'semi-anually'){
				  
				  $dateOneMonthAdded = strtotime(date("Y-m-d", strtotime($expirydate)) . "+6 month");
			  }
			  $newexpirydate = date('Y-m-d',$dateOneMonthAdded);
			  $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
			  $connection->beginTransaction();
			
			  $connection->query("UPDATE hello SET update_time = '".NOW()."',expirydate = '".$newexpirydate."' WHERE customerid = '".$customerid."' AND domain = '".$domain."'");
			  $connection->commit();
			  $update_fields['domain'] = $domain;
			  $update_fields['qty'] = 1;
			  $update_fields['planid'] = $selectedplan;
			  
			  #echo '<pre>';print_r($_POST);
			  $this->_redirect('zmb?step=4');
		}
		
		if($this->getRequest()->getPost('checkout')){
			
			//$this->createzimbra_account(); // call function to create account in zimbra
		}
		
	}
	
	public function createzimbra_account(){
		
		$path=Mage::getBaseDir('code');
	  	require_once($path.'/zm/config.php');
	  	require_once($path.'/zm/Zm/Auth.php');
	  	require_once($path.'/zm/Zm/Account.php');
	  	require_once($path.'/zm/Zm/Domain.php');
      	require_once($path.'/zm/Zm/Server.php');
	   
	    // Loggedin user details
 
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		$customerid=$customer->getId();
		$customername = $customer->getName();
		$customeremail = $customer->getEmail();
		$firstname = $customer->getFirstname();
		$lastname = $customer->getLastname();
		
	 	 //Check login
	  
		$auth = new Zm_Auth($zimbraserver, $zimbraadminemail, $zimbraadminpassword);
		$l = $auth->login();
		if(is_a($l, "Exception")) {
				echo "Error : cannot login to $zimbraserver :-(\n";
				print_exception($l);
				exit();
		}
	    //echo 'testing zimbra login'; die;
	  	//End to check login
		//print_r($l); exit;
 if($l){
	
   $domainManager = new Zm_Domain($auth);   
   // check if account exists
   $accountManager = new Zm_Account($auth);
   
  //print_r($result);
  $mysession = Mage::getModel('core/session')->getData();
  //Fetch domain name from table:zimbrabilling_domains if exists;
  $read= Mage::getSingleton('core/resource')->getConnection('core_read');
  $value=$read->query("SELECT * FROM zimbrabilling_domains where customerid='".$customerid."' AND domain='".$mysession['domain']."' order by id DESC");
  #print_r($value->fetchAll()); die;
  $getalldata = $value->fetchAll();
  if(!empty($getalldata)){
	  foreach($getalldata as $g)
	  {
		 $update = $g['update_time'];
		 $domain = $g['domain'];
		 $email_prefered = $g['email_prefered'];  
	  }
  }else{
		$domain = $mysession['domain'];  
  }

	  $host = parse_url($domain, PHP_URL_HOST);
	  $domain_name = preg_replace('/^(www\.)/i', '', $host);
	  $current_date = date("Y-m-d ", time());
	  $updatetime = date("Y-m-d", strtotime($update));


      $r = $domainManager->getAllDomains();
		 foreach($r['SOAP:ENVELOPE']['SOAP:BODY']['GETALLDOMAINSRESPONSE']['DOMAIN'] as $s){
			 $newarr[] = $s['NAME'];
		 }
		 #print_r($newarr);
		 #die;
		 if(in_array($domain_name,$newarr)){ // check if domain exists for renew and upgrade plan
		   #echo 'exists';
		   $message  ="<p>Domain Information</p>";
		   $message .= "<p><b>Name:</b>".$customername."</p>";
		   $message .= "<p><b>Email:</b>".$customeremail."</p>";
		   $message .= "<p><b>Domain:</b>".$domain_name."</p>";
		   $mail = Mage::getModel('core/email');
		   $mail->setToName($customername);
		   $mail->setToEmail($customeremail);
		   $mail->setBody($message);
		   $mail->setSubject('Account Information');
		   $mail->setType('html');// YOu can use Html or text as Mail format
		   $mail->send();
		 }else{  // create domain in case of purchasing plan
			$newDomain= $domainManager->createDomain($domain_name);
			#echo $domain_name;
			#echo '<pre>';print_r($newDomain);die;
			if($email_prefered!=''){
				$account_name = $email_prefered."@".$newDomain['NAME'];
			}else{
				$account_name=strtolower($firstname).".".strtolower($lastname)."@".$newDomain['NAME'];
			}
			$password = substr(md5(microtime()),rand(0,26),7);;
		   
			$attrs = array("sn"=>$lastname, "gn"=>$firstname, "l"=>"Metropolis");
			$result = $accountManager->accountExists($account_name,"auto");
			$r = $accountManager->createAccount($account_name, $password, $attrs);
			$message = '';

		    $message .='<body style="background:#F6F6F6; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">
<div style="background:#F6F6F6; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">
<table cellspacing="0" cellpadding="0" border="0" height="100%" width="100%">
<tr>
    <td align="center" valign="top" style="padding:20px 0 20px 0">
        <!-- [ header starts here] -->
        <table bgcolor="#FFFFFF" cellspacing="0" cellpadding="10" border="0" width="650" style="border:1px solid #E0E0E0;">
            
        <!-- [ middle starts here] -->
            <tr>
                <td valign="top">
                    <h1 style="font-size:22px; font-weight:normal; line-height:22px; margin:0 0 11px 0;"">Hello, Unnati</h1>
                    <p style="font-size:12px; line-height:16px; margin:0;">Your New Account and Domain Details are:</p>
                </td>
                <tr>
                 <td>
                  <table cellspacing="0" cellpadding="0" border="0" width="650">
                        <thead>
                            <tr>
                                <th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px; padding:5px 9px 6px 9px; line-height:1em;">Domain Information:</th>
                                <th width="10"></th>
                                
                            </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td valign="top" style="font-size:12px; padding:7px 9px 9px 9px; border-left:1px solid #EAEAEA; border-bottom:1px solid #EAEAEA; border-right:1px solid #EAEAEA;">
                                <ul style="list-style:none;line-height:22px;margin:0px;padding:0px;">
                                 <li>Domain : '.$domaininfo.'</li>
                                 <li>Account Email: '.$account_name.'</li>
                                 <li>Password: '.$password.'</li>
                                </ul>
                                <p><b>Login URL: </b><a href="https://gmailer.jumbomailer.com" target="_blank">https://gmailer.jumbomailer.com</a></p>
                            </td>
                            <td>&nbsp;</td>
                            
                        </tr>
                        </tbody>
                    </table>
                 </td>
                </tr>
            </tr>  
        </table>
    </td>
</tr>
</table>
</div>
</body>';
		    $mail = Mage::getModel('core/email');
		    $mail->setToName($customername);
		    $mail->setToEmail($customeremail);
		    $mail->setBody($message);
		    $mail->setSubject('Account Information');
		    $mail->setType('html');// YOu can use Html or text as Mail format
		    $mail->send();
	
		 }		  
    }

	}
	
	public function billingurlAction(){
		#print_r($_POST); die;
		$finalarr = array();
		$read= Mage::getSingleton('core/resource')->getConnection('core_read');
		$getdata = $read->query("SELECT * FROM `zimbrabilling_cycles` WHERE id = '".$_POST['billingid']."'")->fetchObject();
		#print_r($getdata);
		$plandata = $read->query("SELECT * FROM `zimbrabilling_zmb` WHERE zmb_id = '".$_POST['planid']."'")->fetchObject();
		#print_r($plandata);
		$finalarr = array('mailboxes'=>$plandata->mailboxes,'price'=>$getdata->price,'duration'=>$getdata->duration,'title'=>$getdata->title);
		#print_r($finalarr);
		echo json_encode($finalarr);
	}
	
	public function mailboxcountAction(){
	   #echo 'testing';
	   #print_r($_POST);
	   $read= Mage::getSingleton('core/resource')->getConnection('core_read');
	   $count = $read->query("SELECT * FROM `zimbrabilling_mailboxes` WHERE domain = '".$_POST['domain']."'")->fetchAll();
	   $perplanmailbox = $read->query("SELECT * FROM `zimbrabilling_domains` WHERE domain = '".$_POST['domain']."'")->fetchObject();
	   $plandetail = $read->query("SELECT * FROM `zimbrabilling_zmb` WHERE zmb_id = '".$_POST['planid']."'")->fetchObject();
	   $arr = array('count'=>count($count),'planmailboxes'=>$plandetail->mailboxes,'totalallowed'=>$perplanmailbox->mailboxes,'optedplanid'=>$perplanmailbox->planid);
	   echo json_encode($arr);
	   #echo count($count);	
	}
	
	public function renewplanAction(){
		print_r($_POST);	
		#die;
	}
	
 }