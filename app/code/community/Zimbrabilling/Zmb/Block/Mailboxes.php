<?php
/**
 * Mailboxes List block
 *
 * @author Magento
 */
class Zimbrabilling_Zmb_Block_Mailboxes extends Mage_Core_Block_Template
{
   public function _prepareLayout()
    {
		
		return parent::_prepareLayout();
    }
	
	public function getCreatemailboxUrl(){
		#return $this->getUrl('create-mailboxes');
		#echo 'testing';
		# die;
    	return $this->getUrl('zmb/mailboxes/createmailboxes'); // goes to createmailboxesAction() in controllers
	}
	
	public function getDisplaymailboxUrl(){
		#return $this->getUrl('hello/list/displaymailbox');
		return $this->getUrl('mailboxes');
	}
	
	public function getdeleteUrl(){
	   return $this->getUrl('zmb/mailboxes/deleteaccount');	
	}
	
	public function getresetpwdUrl(){
	   return $this->getUrl('zmb/mailboxes/resetpwd');	
	}
}
