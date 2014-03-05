<?php
class Zimbrabilling_Zmb_Block_Upgrade extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		
		return parent::_prepareLayout();
    }
    
     public function getList()     
     { 
        if (!$this->hasData('upgrade')) {
            $this->setData('upgrade', Mage::registry('upgrade'));
        }
        return $this->getData('upgrade');
        
    }
	
	public function getPostUrlMailbox(){
		//echo "mkm";die;
		return $this->getUrl('zmb/upgrade/postx');

	}
	
	public function getCreatemailboxUrl(){
		#return $this->getUrl('create-mailboxes');
    	return $this->getUrl('zmb/upgrade/upgrade');
	}
	
	public function getDisplaymailboxUrl(){
		//echo "mkm";die;
		#return $this->getUrl('hello/list/displaymailbox');
		return $this->getUrl('upgrade');
	}
	
	public function getdeleteUrl(){
	   return $this->getUrl('hello/upgrade/deleteaccount');	
	}
	
	public function getresetpwdUrl(){
	   return $this->getUrl('hello/upgrade/resetpwd');	
	}
	
}