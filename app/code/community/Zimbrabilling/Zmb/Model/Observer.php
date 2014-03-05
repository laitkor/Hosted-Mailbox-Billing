<?php  
class Zimbrabilling_Zmb_Model_Observer
{
    /**
     * Event before show news item on frontend
     * If specified new post was added recently (term is defined in config) we'll see message about this on front-end.
     *
     * @param Varien_Event_Observer $observer
     */
    public function beforeZmbDisplayed(Varien_Event_Observer $observer)
    {
        $newsItem = $observer->getEvent()->getNewsItem();
        $currentDate = Mage::app()->getLocale()->date();
        $newsCreatedAt = Mage::app()->getLocale()->date(strtotime($newsItem->getCreatedAt()));
        $daysDifference = $currentDate->sub($newsCreatedAt)->getTimestamp() / (60 * 60 * 24);
        if ($daysDifference < Mage::helper('zimbrabilling_zmb')->getDaysDifference()) {
            Mage::getSingleton('core/session')->addSuccess(Mage::helper('zimbrabilling_zmb')->__('Recently added'));
        }
    }	
	
	public function setcron(){
	  #echo 'test';	
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
	
	$mail = Mage::getModel('core/email');
    $read= Mage::getSingleton('core/resource')->getConnection('core_read');
    $value=$read->query("SELECT * FROM zimbrabilling_domains");
    $selrec = $value->fetchAll();
	#print_r($selrec);
	#die;
	foreach($selrec as $s){
		// get customer details from customer id
		$customer = Mage::getModel('customer/customer')->load($s['customerid']);
		$firstname = $customer->getFirstname();
  		$lastname = $customer->getLastname();
		$customername = $firstname." ".$lastname;
	    $customeremail = $customer->getEmail();
		$regdomain = $s['domain'];
		#$amount = '';

		$currdate = date('Y-m-d',strtotime(NOW()));
		$datetime1 = $currdate;
		#echo '<br/>';
		#$datetime1 = '2014-05-21';
		$datetime2 = date('Y-m-d',strtotime($s['expiry_date']));

	    $dStart = new DateTime($datetime1);
		$dEnd = new DateTime($datetime2);
		$dDiff = $dStart->diff($dEnd);
		$interval = $dDiff->days;
		$at = $dDiff->format('%R') ;

		
		if(($interval <= '7' && ($s['renew_status'] == '') && $at == '+')){
			/*if($s['upgrade_price']!=''){
				$amount = $s['upgrade_price'] + substr_replace($s['plan_price']," ",0,1);
			}else{
				$amount = substr_replace($s['plan_price']," ",0,1);	
			}*/
			$sql = $read->query("SELECT * FROM `zimbrabilling_cycles` WHERE id = '".$s['billingcycle']."'")->fetchObject();
			$upgradeprice = $s['additional_mailboxes']* substr_replace($sql->per_mailbox_price," ",0,1);
			$amount = substr_replace($s['plan_price']," ",0,1)+$upgradeprice;
			#echo $amount; die;
			$cudomain= 'http://'.$_SERVER['HTTP_HOST'];
			$cust_id = $s['customerid'];
			#echo $cust_id;
			#die;
			$message = '';
			
			$message .='<body style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">
<div style="background:#F6F6F6;font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">
<table cellspacing="0" cellpadding="0" border="0" height="100%" width="100%">
<tr>
    <td align="center" valign="top" style="padding:20px 0 20px 0">
        <!-- [ header starts here] -->
        <table bgcolor="#FFFFFF" cellspacing="0" cellpadding="10" border="0" width="650" style="border:1px solid #E0E0E0;">
            
        <!-- [ middle starts here] -->
            <tr>
                <td valign="top">
                    <h1 style="font-size:22px; font-weight:normal; line-height:22px; margin:0 0 11px 0;"">Hi '.$customername.'</h1>
                    <p style="font-size:12px; line-height:25px; margin:0;">We would like to remind you that the plan <b>'.$s['plan_title'].'</b> will expire within the next 1 week on <b>'.date('F d,Y',strtotime($s['expiry_date'])).' </b>. To avoid deactivation of accounts please make the payment of <b>$'.$amount.'</b> by <b>'.date('F d,Y',strtotime($s['expiry_date'])).'</b>. To make the payment <a href="'.$cudomain.'/zmb?step=3&customerid='.$cust_id.'&domain='.$regdomain.'"> Click here</a></p>
                </td>
            </tr> 
			<tr><td>Thanks<br/>The team at Hosted Mailbox Billing</td></tr> 
        </table>
    </td>
</tr>
</table>
</div>
</body>';
			
			$mail->setToName($customername);
			$mail->setToEmail($customeremail);
			$mail->setBody($message);
			$mail->setSubject('Order Renewal Notification');
			//$mail->setFromEmail('no-reply@laitkor.com');
			$mail->setFromName("Notification");
			$mail->setType('html');// YOu can use Html or text as Mail format.
			
			$mail->send();
			
			/*****update domains table for renew status******/
			 $write= Mage::getSingleton('core/resource')->getConnection('core_write');
			 $write->query("UPDATE zimbrabilling_domains SET renew_status = 'mailsent' WHERE customerid = '".$cust_id."' AND domain = '".$regdomain."'");
			 $write->commit(); 
			
			
		    /*********ends here******************/
		}
		
		if(($interval == '1' && ($s['renew_status'] == 'mailsent')&& $at == '+')){
			/*if($s['upgrade_price']!=''){
				$amount = $s['upgrade_price'] + substr_replace($s['plan_price']," ",0,1);
			}else{
				$amount = substr_replace($s['plan_price']," ",0,1);	
			}*/
			$sql = $read->query("SELECT * FROM `zimbrabilling_cycles` WHERE id = '".$s['billingcycle']."'")->fetchObject();
			$upgradeprice = $s['additional_mailboxes']* substr_replace($sql->per_mailbox_price," ",0,1);
			$amount = substr_replace($s['plan_price']," ",0,1)+$upgradeprice;
			#echo $amount; die;
			$cudomain= 'http://'.$_SERVER['HTTP_HOST'];
			$cust_id = $s['customerid'];
			#echo $cust_id;
			#die;
			$alertmessage = '';
			
			$alertmessage .='<body style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">
<div style="background:#F6F6F6;font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">
<table cellspacing="0" cellpadding="0" border="0" height="100%" width="100%">
<tr>
    <td align="center" valign="top" style="padding:20px 0 20px 0">
        <!-- [ header starts here] -->
        <table bgcolor="#FFFFFF" cellspacing="0" cellpadding="10" border="0" width="650" style="border:1px solid #E0E0E0;">
            
        <!-- [ middle starts here] -->
            <tr>
                <td valign="top">
                    <h1 style="font-size:22px; font-weight:normal; line-height:22px; margin:0 0 11px 0;"">Hi '.$customername.'</h1>
                    <p style="font-size:12px; line-height:25px; margin:0;">We had written you couple of days back , about the payment of <b>$'.$amount.'</b> in respect of account expiry of your plan <b>'.$s['plan_title'].'</b> which has probably escaped your attention. Kindly make the payment in order to avoid deactivation of the accounts.</p><p style="font-size:12px; line-height:25px; margin:0;"> We want your business and we want you to feel that you are the privileged customer, you really are. You will get continued good service in exchange of prompt payment. To make the payment <a href="'.$cudomain.'/zmb?step=3&customerid='.$cust_id.'&domain='.$regdomain.'"> Click here</a></p>
                </td>
            </tr> 
			<tr><td>Thanks<br/>The team at Hosted Mailbox Billing</td></tr> 
        </table>
    </td>
</tr>
</table>
</div>
</body>';
			
			$mail->setToName($customername);
			$mail->setToEmail($customeremail);
			$mail->setBody($alertmessage);
			$mail->setSubject('Order Renewal Notification');
			//$mail->setFromEmail('no-reply@laitkor.com');
			$mail->setFromName("Notification");
			$mail->setType('html');// YOu can use Html or text as Mail format.
			$mail->send();
		}
		
		if($datetime1>$datetime2){
		  if($l){
		    // check if account exists
   			$accountManager = new Zm_Account($auth);	  
			#echo 'expired';
			
			$host = parse_url($regdomain, PHP_URL_HOST);
	  	    $domain_name = preg_replace('/^(www\.)/i', '', $host);
			$expireddata = $read->query("SELECT * FROM `zimbrabilling_mailboxes` WHERE domain = '".$s['domain']."'")->fetchAll();
			#print_r($expireddata);
			$sql1 = $read->query("SELECT * FROM `zimbrabilling_domains` WHERE domain = '".$s['domain']."'")->fetchObject();
			if($sql1->email_prefered!=''){
				$email_prefered = $sql1->email_prefered;
				$account_name = $email_prefered."@".$domain_name;
			}else{
				$account_name=strtolower($firstname).".".strtolower($lastname)."@".$domain_name;
			}
			#echo $account_name; die;
			$accountManager->setAccountStatus($account_name,"locked");
			foreach($expireddata as $e){
				// set account status to "locked"
				$accountManager->setAccountStatus($e['email'],"locked");
			 }
		  }
		}
		
	} // endforeach;
	#die;
	}
	
}  

