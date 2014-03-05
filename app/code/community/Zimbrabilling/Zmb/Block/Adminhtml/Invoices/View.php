<?php

class Zimbrabilling_Zmb_Block_Adminhtml_Invoices_View extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {   
        $this->_objectId   = 'id';
        $this->_blockGroup = 'zimbrabilling';
        $this->_controller = 'adminhtml_invoices';
        
        parent::__construct();
		#$this->loadLayout();
		#$this->getLayout()->getBlock('root')->setTemplate('adminhtml/zimbrabilling/sales/index.phtml');
		#$this->renderLayout();
		
	
    }

    /**
     * Retrieve text for header element depending on loaded page
     *
     * @return string
     */
/*    public function getHeaderText()
    {    
        $model = Mage::helper('zimbrabilling_zmb')->getInvoicesItemInstance();
		print_r($model); die;
        if ($model->getId()) {
            return Mage::helper('zimbrabilling_zmb')->__("Edit Plan '%s'",
                 $this->escapeHtml($model->getTitle()));
        } else {
            return Mage::helper('zimbrabilling_zmb')->__('New Plan');
        }
    }*/
	
	public function getUrlsendmail(){
		#echo 'hello'; #die;
		return $this->getUrl('*/*/sendmail');	
	}
}