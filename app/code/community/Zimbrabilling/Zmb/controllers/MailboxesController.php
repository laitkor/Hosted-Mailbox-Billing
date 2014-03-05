<?php
class Zimbrabilling_Zmb_MailboxesController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
			$this->loadLayout();     
	        $this->renderLayout();
	}
	
	public function displaymailboxAction(){
		#print_r($_POST); die;
		#if(isset($_POST['go']) && $_POST['go'] == 'Submit'){
		  $helloid = $_POST['selectedrecord'];
		  $selecteddomain = $_POST['selecteddomain'];
		  $resource = Mage::getSingleton('core/resource');
		  $read= $resource->getConnection('core_read');	
		  $getdata = $read->query("SELECT * FROM zimbrabilling_domains WHERE id = '".$helloid."'")->fetchObject();
		  $getmailboxes = $read->query("SELECT * FROM zimbrabilling_mailboxes WHERE domain = '".$selecteddomain."'")->fetchAll();
		  #print_r($getdata);
       #}
	}
	
	public function createmailboxesAction(){ // data posted from getCreatemailboxUrl() of mailboxes.php block
		#echo 'testing';
	   	#print_r($_POST);
		#die;
		if(isset($_POST['submit']) && !empty($_POST['submit'])){  
		       
			 $msg = ''; 
			//Get Post data
			$postusername=$this->getRequest()->getPost('username');
		
			$postpwd = $this->getRequest()->getPost('password');
			$domainname = $this->getRequest()->getPost('domainname');
	
			$fulldomain = $this->getRequest()->getPost('fulldomain');
			$path=Mage::getBaseDir('code');
			require_once($path.'/zm/config.php');
			require_once($path.'/zm/Zm/Auth.php');
			require_once($path.'/zm/Zm/Account.php');
			require_once($path.'/zm/Zm/Domain.php');
			require_once($path.'/zm/Zm/Server.php');
		  
			$auth = new Zm_Auth($zimbraserver, $zimbraadminemail, $zimbraadminpassword);
			$l = $auth->login();
			if(is_a($l, "Exception")) {
				echo "Error : cannot login to $zimbraserver :-(\n";
				print_exception($l);
				exit();
			}
			
			$accountManager = new Zm_Account($auth);
			$customer = Mage::getSingleton('customer/session')->getCustomer();
			$customerid = $customer->getId();
			$customeremail = $customer->getEmail();
			$fname = $customer->getFirstname();
			$lname = $customer->getLastname();
				 foreach($postusername as $key =>$pdata)
				 {
					$firstname=$pdata;
					$lastname="";
					$account_name=$firstname."@".$domainname;
					$password =  $postpwd[$key];
					#$password = substr(md5(microtime()),rand(0,26),7);
					$attrs = array("sn"=>$lastname, "gn"=>$firstname, "l"=>"Metropolis");
					$result = $accountManager->accountExists($account_name,"auto");
				
					if(!empty($result)){
						echo 'This Account already exists:'.$account_name.'.Please try with other username.<br/>';
					}else{
					  $r = $accountManager->createAccount($account_name, $password, $attrs); 
					  $insert= Mage::getSingleton('core/resource')->getConnection('core_write');
					  $insert->beginTransaction();
					  $fields = array();
					  if($firstname!='' && $password!=''){
						$fields['email']= $account_name;
						$fields['customerid'] = $customerid;
						$fields['domain'] = $fulldomain;
						$insert->insert('zimbrabilling_mailboxes', $fields);
						$insert->commit();
						#print_r($fields);
						
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
                    <h1 style="font-size:22px; font-weight:normal; line-height:22px; margin:0 0 11px 0;"">Hi '.$fname.'&nbsp;'.$lname.'</h1>
                    <p style="font-size:12px; line-height:16px; margin:0;">You just created an additional account for your domain '.$domainname.'. The login details for this account are as follows:</p>
                </td>
                <tr>
                 <td>
                  <table cellspacing="0" cellpadding="0" border="0" width="650">
                        <thead>
                            <tr>
                                <th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px; padding:5px 9px 6px 9px; line-height:1em;">Account Information:</th>
                                <th width="10"></th>
                                
                            </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td valign="top" style="font-size:12px; padding:7px 9px 9px 9px; border-left:1px solid #EAEAEA; border-bottom:1px solid #EAEAEA; border-right:1px solid #EAEAEA;">
                                <ul style="list-style:none;line-height:22px;margin:0px;padding:0px;">
								 <li><b>Login URL: </b><a href="https://gmailer.jumbomailer.com" target="_blank">https://gmailer.jumbomailer.com</a></li>
                                 <li><b>Email: </b>'.$account_name.'</li>
                                 <li><b>Password: </b>'.$password.'</li>
                                </ul>
                                
                            </td>
                            <td>&nbsp;</td>
                            
                        </tr>
                        </tbody>
                    </table>
                 </td>
                </tr>
				<tr><td>Cheers!<br/>The team at Hosted Mailbox Billing</td></tr>
            </tr>  
        </table>
    </td>
</tr>
</table>
</div>
</body>';
						
					//Customer Names
					$customername=$firstname;
					
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
			 
			 #$this->_redirect('hello');
			 #$msg = "Mailboxes created...";
			 $this->_redirect('mailboxes?msg=success');
	 }
  }
	
	  public function deleteaccountAction(){
		  #print_r($_POST['mailboxid']);
		  #echo "test"; die;  
		    $mailboxid = $_POST['mailboxid'];
		    $resource = Mage::getSingleton('core/resource');
		    $read= $resource->getConnection('core_read');	
			$getemail = $read->query("SELECT * FROM zimbrabilling_mailboxes WHERE intID = '".$mailboxid."'")->fetchObject();
			$email = $getemail->email;
			#echo $email;
		    $path=Mage::getBaseDir('code');
			require_once($path.'/zm/config.php');
			require_once($path.'/zm/Zm/Auth.php');
			require_once($path.'/zm/Zm/Account.php');
			require_once($path.'/zm/Zm/Domain.php');
			require_once($path.'/zm/Zm/Server.php');
		  
			$auth = new Zm_Auth($zimbraserver, $zimbraadminemail, $zimbraadminpassword);
			$l = $auth->login();
			if(is_a($l, "Exception")) {
				echo "Error : cannot login to $zimbraserver :-(\n";
				print_exception($l);
				exit();
			}
			$accountManager = new Zm_Account($auth);
			$result = $accountManager->deleteAccount($email,"auto");
			// Delete from mailboxes table

			$write = Mage::getSingleton('core/resource')->getConnection('core_write');
			$write->query("DELETE FROM zimbrabilling_mailboxes  WHERE intID = '".$mailboxid."'");
			$write->commit();
            
			echo 'account deleted';
			#print_r($result);
		  
	  }
	  
	  public function resetpwdAction(){
			#echo 'testing'; die;
			$customer = Mage::getSingleton('customer/session')->getCustomer();
			$customerid = $customer->getId();
			$customeremail = $customer->getEmail();
			$firstname = $customer->getFirstname();
			$lastname = $customer->getLastname();

			$mailboxid = $_POST['mailboxid'];
		    $resource = Mage::getSingleton('core/resource');
		    $read= $resource->getConnection('core_read');	
			$getemail = $read->query("SELECT * FROM zimbrabilling_mailboxes WHERE intID = '".$mailboxid."'")->fetchObject();
			$email = $getemail->email;
			#echo $email;
		    $path=Mage::getBaseDir('code');
			require_once($path.'/zm/config.php');
			require_once($path.'/zm/Zm/Auth.php');
			require_once($path.'/zm/Zm/Account.php');
			require_once($path.'/zm/Zm/Domain.php');
			require_once($path.'/zm/Zm/Server.php');
		  
			$auth = new Zm_Auth($zimbraserver, $zimbraadminemail, $zimbraadminpassword);
			$l = $auth->login();
			if(is_a($l, "Exception")) {
				echo "Error : cannot login to $zimbraserver :-(\n";
				print_exception($l);
				exit();
			}
			$accountManager = new Zm_Account($auth);
			$password = substr(md5(microtime()),rand(0,26),7);
			$result = $accountManager->setAccountPassword($email,$password,"auto");
			// Delete from mailboxes table
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
                    <h1 style="font-size:22px; font-weight:normal; line-height:22px; margin:0 0 11px 0;"">Hello, '.$firstname.'&nbsp;'.$lastname.'</h1>
                    <p style="font-size:12px; line-height:16px; margin:0;">The password for requested account '.$email.' has been reset:</p>
                </td>
                <tr>
                 <td>
                  <table cellspacing="0" cellpadding="0" border="0" width="650">
                        <thead>
                            <tr>
                                <th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px; padding:5px 9px 6px 9px; line-height:1em;">Account Information:</th>
                                <th width="10"></th>
                                
                            </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td valign="top" style="font-size:12px; padding:7px 9px 9px 9px; border-left:1px solid #EAEAEA; border-bottom:1px solid #EAEAEA; border-right:1px solid #EAEAEA;">
                                <ul style="list-style:none;line-height:22px;margin:0px;padding:0px;">
                                 <li>Account Email: '.$email.'</li>
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
			
			//Customer Names
			//$customername=$firstname;
			
			$mail = Mage::getModel('core/email');
			//$mail->setToName($customername);
			$mail->setToEmail($customeremail);
			$mail->setBody($message);
			$mail->setSubject('Account Information');
			$mail->setType('html');// YOu can use Html or text as Mail format
			$mail->send();
			
			
			echo 'password changed';  
	  }
}
?>