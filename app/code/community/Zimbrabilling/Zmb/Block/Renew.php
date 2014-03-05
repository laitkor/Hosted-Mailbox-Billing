<?php
class Zimbrabilling_Zmb_Block_Renew extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		
		return parent::_prepareLayout();
    }
    
	public function getrenewUrl(){
		#return $this->getUrl('create-mailboxes');
    	return $this->getUrl('zmb/renew/renew');
	}
}