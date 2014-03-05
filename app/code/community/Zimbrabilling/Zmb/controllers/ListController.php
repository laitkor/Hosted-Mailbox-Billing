<?php
class Zimbrabilling_Zmb_ListController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
			$this->loadLayout();     
	        $this->renderLayout();
	}
	
	public function displaymailboxAction(){
		#if(isset($_POST['go']) && $_POST['go'] == 'Submit'){
		  $helloid = $_POST['selectedrecord'];
		  $selecteddomain = $_POST['selecteddomain'];
		  $resource = Mage::getSingleton('core/resource');
		  $read= $resource->getConnection('core_read');	
		  $getdata = $read->query("SELECT * FROM hello WHERE hello_id = '".$helloid."'")->fetchObject();
		  $getmailboxes = $read->query("SELECT * FROM mailboxes WHERE domain = '".$selecteddomain."'")->fetchAll();
		  #print_r($getdata);
       #}
	}
	
	public function createmailboxesAction(){
	    #print_r($_POST);	
		if(isset($_POST['submit']) && !empty($_POST['submit'])){  
			 $msg = ''; 
			//Get Post data
			$postusername=$this->getRequest()->getPost('username');
		
			$postpwd = $this->getRequest()->getPost('password');
			$domainname = $this->getRequest()->getPost('domainname');
	
			$fulldomain = $this->getRequest()->getPost('fulldomain');
			$path=Mage::getBaseDir('code');
			require_once($path.'/local/Unnati/Hello/zm/config.php');
			require_once($path.'/local/Unnati/Hello/zm/Zm/Auth.php');
			require_once($path.'/local/Unnati/Hello/zm/Zm/Account.php');
			require_once($path.'/local/Unnati/Hello/zm/Zm/Domain.php');
			require_once($path.'/local/Unnati/Hello/zm/Zm/Server.php');
		  
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
						$insert->insert('mailboxes', $fields);
						$insert->commit();
						#print_r($fields);
						
						$message = '<p>Your New Account and Domain Details are:</p>';
						$message .= "\r\n".'<p><b>Account Email:</b> '.$account_name.'</p>';
						$message .= "\r\n".'<p><b>Password: </b>'.$password.'</p>';
						$message .= "\r\n".'<p><b>Login URL: </b><a href="https://gmailer.jumbomailer.com" target="_blank">https://gmailer.jumbomailer.com</a></p>';
						
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
			 $msg = "Mailboxes created...";
			 $this->_redirect('create-mailboxes');
	 }
  }
	
	  public function deleteaccountAction(){
		  #print_r($_POST['mailboxid']);
		  #echo "test";  
		    $mailboxid = $_POST['mailboxid'];
		    $resource = Mage::getSingleton('core/resource');
		    $read= $resource->getConnection('core_read');	
			$getemail = $read->query("SELECT * FROM mailboxes WHERE intID = '".$mailboxid."'")->fetchObject();
			$email = $getemail->email;
			#echo $email;
		    $path=Mage::getBaseDir('code');
			require_once($path.'/local/Unnati/Hello/zm/config.php');
			require_once($path.'/local/Unnati/Hello/zm/Zm/Auth.php');
			require_once($path.'/local/Unnati/Hello/zm/Zm/Account.php');
			require_once($path.'/local/Unnati/Hello/zm/Zm/Domain.php');
			require_once($path.'/local/Unnati/Hello/zm/Zm/Server.php');
		  
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
			$write->query("DELETE FROM mailboxes  WHERE intID = '".$mailboxid."'");
			$write->commit();
            
			echo 'account deleted';
			#print_r($result);
		  
	  }
	  
	  public function resetpwdAction(){
			#echo 'testing'; die;
			$customer = Mage::getSingleton('customer/session')->getCustomer();
			$customerid = $customer->getId();
			$customeremail = $customer->getEmail();

			$mailboxid = $_POST['mailboxid'];
		    $resource = Mage::getSingleton('core/resource');
		    $read= $resource->getConnection('core_read');	
			$getemail = $read->query("SELECT * FROM mailboxes WHERE intID = '".$mailboxid."'")->fetchObject();
			$email = $getemail->email;
			#echo $email;
		    $path=Mage::getBaseDir('code');
			require_once($path.'/local/Unnati/Hello/zm/config.php');
			require_once($path.'/local/Unnati/Hello/zm/Zm/Auth.php');
			require_once($path.'/local/Unnati/Hello/zm/Zm/Account.php');
			require_once($path.'/local/Unnati/Hello/zm/Zm/Domain.php');
			require_once($path.'/local/Unnati/Hello/zm/Zm/Server.php');
		  
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

			/*$write = Mage::getSingleton('core/resource')->getConnection('core_write');
			$write->query("UPDATE mailboxes SET password = '".$password."' WHERE intID = '".$mailboxid."'");
			$write->commit();*/
            
			$message = '<p>Your Password has been reset:</p>';
			$message .= "\r\n".'<p><b>Account Email:</b> '.$email.'</p>';
			$message .= "\r\n".'<p><b>Password: </b>'.$password.'</p>';
			$message .= "\r\n".'<p><b>Login URL: </b><a href="https://gmailer.jumbomailer.com" target="_blank">https://gmailer.jumbomailer.com</a></p>';
			
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