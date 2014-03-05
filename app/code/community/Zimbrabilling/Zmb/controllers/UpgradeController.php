<?php
class Zimbrabilling_Zmb_UpgradeController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
			$this->loadLayout();     
	        $this->renderLayout();
	}
	public function postxAction()
	{  #print_r($_POST);
		#die;
		if($this->getRequest()->getPost('upgradationx')){
			  $session = Mage::getModel('core/session');
			  $nomailbox= $this->getRequest()->getPost('nomailbox');
			 
			  $zmbid= $this->getRequest()->getPost('order_id');
			  $planid = $this->getRequest()->getPost('planid');
			  $customer = Mage::getSingleton('customer/session')->getCustomer();
	          $customerid = $customer->getId();
			  $resource = Mage::getSingleton('core/resource');
			  $read= $resource->getConnection('core_read');
			  //$domain = $this->getRequest()->getPost('alreadyselecteddomain');
			  
			 
			  $getdata = $read->query("SELECT * FROM zimbrabilling_domains WHERE id = '".$zmbid."'")->fetchObject();
			  #$product = Mage::getModel('catalog/product')->load($getdata->planid);
			  $product = $read->query("SELECT * FROM zimbrabilling_zmb WHERE zmb_id = '".$planid."'")->fetchObject();
			  #echo "SELECT * FROM zimbrabilling_cycles WHERE id = '".$getdata->billingcycle."'";
			  #die;
			  $billingcycle = $read->query("SELECT * FROM zimbrabilling_cycles WHERE id = '".$getdata->billingcycle."' ")->fetchObject();
			  #print_r($billingcycle); die;
			  #print_r($product);
			  $boxprice=$billingcycle->per_mailbox_price;
			  #$boxprice = '12';
			  #echo $boxprice.'<br/>';
			  #die;
			  $strpos = '';
			 # $strpos = strpos($boxprice, "$").'<br/>';
			  $strpos = substr($boxprice,0,1);
			  #echo substr($boxprice,0,1);
			  #echo $strpos.'<br/>';
			  if($strpos == '$'){
				 
				  #echo $boxprice.'<br/>';
				  $bprice = substr_replace($boxprice," ",0,1);
				  $total= $bprice*$nomailbox;
				  
			  }else{
				  #echo $boxprice;
				  $total= $boxprice*$nomailbox;
			  }
			  
			  #echo $total; 
			  #die;
			  $domain=$getdata->domain;
			  $totalmailboxes = $getdata->mailboxes + $nomailbox;
			  //$host = parse_url($domain, PHP_URL_HOST);
	          //$domain = preg_replace('/^(www\.)/i', '', $host); 
			  $session->setData('totalprice',$total);
			  $session->setData('domain',$domain);
			  $session->setData('planname',$getdata->plan_title);
			  $session->setData('mailboxes',$totalmailboxes);
			  $session->setData('domain',$domain);
			  $session->setData('billingcycle',$billingcycle->title);
			  $session->setData('permailboxprice',$boxprice);
			  $session->setData('additionalmailboxes',$nomailbox);
			  $session->setData('earliermailboxes',$getdata->mailboxes);
			  $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
			  $connection->beginTransaction();
              
			 # $connection->query("UPDATE zimbrabilling_domains SET additional_mailbox='".$nomailbox."',mailboxes = mailboxes+'".$nomailbox."' WHERE customerid = '".$customerid."' AND domain = '".$domain."'");
			 # $connection->commit();
			  
			  $this->_redirect('upgrade?page=details');
		}
	}
	  
}
?>