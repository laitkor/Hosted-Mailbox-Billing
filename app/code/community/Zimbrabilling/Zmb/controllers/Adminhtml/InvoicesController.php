<?php

class Zimbrabilling_Zmb_Adminhtml_InvoicesController extends Mage_Adminhtml_Controller_Action
{ 

    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        $this->loadLayout()
            ->_setActiveMenu('zmb/invoices')
            ->_addBreadcrumb(
                  Mage::helper('zimbrabilling_zmb')->__('Zmb'),
                  Mage::helper('zimbrabilling_zmb')->__('Zmb')
              )
            ->_addBreadcrumb(
                  Mage::helper('zimbrabilling_zmb')->__('Invoices'),
                  Mage::helper('zimbrabilling_zmb')->__('Invoices')
              )
        ;
        return $this;
    }
    public function indexAction()
    {
        $this->_title($this->__('Zimbra Plans'))
             ->_title($this->__('Invoices'));

        $this->_initAction();
        $this->renderLayout();
    }

	public function editAction() {
		
        $this->_title($this->__('Zimbra Plans'))
             ->_title($this->__('Invoices'));

        // 1. instance news model
        /* @var $model Magentostudy_News_Model_Item */
        $model = Mage::getModel('zimbrabilling_zmb/invoices');
        #echo '<pre>';
		#print_r($model);
        // 2. if exists id, check it and load data
        $newsId = $this->getRequest()->getParam('id');
        if ($newsId) {
            $a = $model->load($newsId);
			#echo '<pre>';print_r($a);
            if (!$model['id']) {
                $this->_getSession()->addError(
                    Mage::helper('zimbrabilling_zmb')->__('News item does not exist.')
                );
                return $this->_redirect('*/*/');
            }
            // prepare title
            $this->_title($model['id']);
            $breadCrumb = Mage::helper('zimbrabilling_zmb')->__('Edit Item');
        } else {
            $this->_title(Mage::helper('zimbrabilling_zmb')->__('New Item'));
            $breadCrumb = Mage::helper('zimbrabilling_zmb')->__('New Item');
        }

        // Init breadcrumbs
        $this->_initAction()->_addBreadcrumb($breadCrumb, $breadCrumb);

        // 3. Set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        // 4. Register model to use later in blocks
        Mage::register('zmb_item', $model);

        // 5. render layout
        $this->renderLayout();
    }
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
        $redirectPath   = '*/*';
        $redirectParams = array();

        // check if data sent
        $data = $this->getRequest()->getPost();
        if ($data) {
            $data = $this->_filterPostData($data);
			#print_r($data); 
			#echo $data['mailboxes'];
			#die;
            // init model and set data
            /* @var $model Magentostudy_News_Model_Item */
            $model = Mage::getModel('zimbrabilling_zmb/zmb');
			

            // if news item exists, try to load it
            $newsId = $this->getRequest()->getParam('zmb_id');
			#$product = Mage::getModel('catalog/product')->load($newsId);
			$write = Mage::getSingleton('core/resource')->getConnection('core_write');
			#echo $newsId; die;
            if ($newsId) {
                $model->load($newsId);
				$delete = "DELETE FROM zimbrabilling_cycles WHERE planid = '".$newsId."'";
				$write->query($delete);
				#echo count($data['billingcycle_title']);
				for($i=0;$i<count($data['billingcycle_title']);$i++){
				  #$sql = "INSERT INTO zimbrabilling_cycles(planid,title,price,duration)VALUES('".$lastInsertId."','".$data['billingcycle_title'][$i]."','".$data['billingcycle_price'][$i]."','".$data['billingcycle_details'][$i]."')";

				  $sql1 = "INSERT INTO zimbrabilling_cycles(planid,title,price,duration)VALUES('".$newsId."','".$data['billingcycle_title'][$i]."','".$data['billingcycle_price'][$i]."','".$data['billingcycle_details'][$i]."')";
				  #echo $sql1.'<br/>';
				  $write->query($sql1);
				 
			    }
				
            }
			#die;
            // save image data and remove from data array
            if (isset($data['image'])) {
                $imageData = $data['image'];
                unset($data['image']);
            } else {
                $imageData = array();
            }

			
			#echo '<pre>';
			#print_r($data);
			#die;
            $model->addData($data);
			 
			
            try {
                $hasError = false;
                /* @var $imageHelper Magentostudy_News_Helper_Image */
                $imageHelper = Mage::helper('zimbrabilling_zmb/image');
                // remove image

                if (isset($imageData['delete']) && $model->getImage()) {
                    $imageHelper->removeImage($model->getImage());
                    $model->setImage(null);
                }

                // upload new image
                $imageFile = $imageHelper->uploadImage('image');
                if ($imageFile) {
                    if ($model->getImage()) {
                        $imageHelper->removeImage($model->getImage());
                    }
                    $model->setImage($imageFile);
                }
                // save the data
                $model->save();

                // display success message
                $this->_getSession()->addSuccess(
                    Mage::helper('zimbrabilling_zmb')->__('The plan has been saved.')
                );

                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $redirectPath   = '*/*/edit';
                    $redirectParams = array('id' => $model->getId());
                }
            } catch (Mage_Core_Exception $e) {
                $hasError = true;
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $hasError = true;
                $this->_getSession()->addException($e,
                    Mage::helper('zimbrabilling_zmb')->__('An error occurred while saving the plan.')
                );
            }

            if ($hasError) {
                $this->_getSession()->setFormData($data);
                $redirectPath   = '*/*/edit';
                $redirectParams = array('id' => $this->getRequest()->getParam('id'));
            }
			
			if($newsId == ''){
			   $connection    = Mage::getSingleton('core/resource')->getConnection('core_read');
			   $lastInsertId  = $connection->fetchOne('SELECT * FROM `zimbrabilling_zmb` ORDER BY zmb_id DESC LIMIT 1');
			   #echo $lastInsertId;
			   #die;
  
			   
		  
			   for($i=0;$i<count($data['billingcycle_title']);$i++){
				   $sql = "INSERT INTO zimbrabilling_cycles(planid,title,price,duration)VALUES('".$lastInsertId."','".$data['billingcycle_title'][$i]."','".$data['billingcycle_price'][$i]."','".$data['billingcycle_details'][$i]."')";
				   $write->query($sql);   
			   }
			
			}
        }

        $this->_redirect($redirectPath, $redirectParams);
    }
 
	    /**
     * Delete action
     */
    public function deleteAction()
    {
        // check if we know what should be deleted
        $itemId = $this->getRequest()->getParam('id');
        if ($itemId) {
            try {
                // init model and delete
                /** @var $model Magentostudy_News_Model_Item */
                $model = Mage::getModel('zimbrabilling_zmb/zmb');
                $model->load($itemId);
                if (!$model->getId()) {
                    Mage::throwException(Mage::helper('zimbrabilling_zmb')->__('Unable to find a plan.'));
                }
                $model->delete();

                // display success message
                $this->_getSession()->addSuccess(
                    Mage::helper('zimbrabilling_zmb')->__('The plan has been deleted.')
                );
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException($e,
                    Mage::helper('zimbrabilling_zmb')->__('An error occurred while deleting the plan.')
                );
            }
        }

        // go to grid
        $this->_redirect('*/*/');
    }

    /**
     * Check the permission to run it
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        switch ($this->getRequest()->getActionName()) {
            case 'new':
            case 'save':
                return Mage::getSingleton('admin/session')->isAllowed('invoices/manage/save');
                break;
            case 'delete':
                return Mage::getSingleton('admin/session')->isAllowed('invoices/manage/delete');
                break;
            default:
                return Mage::getSingleton('admin/session')->isAllowed('invoices/manage');
                break;
        }
    }

    /**
     * Filtering posted data. Converting localized data if needed
     *
     * @param array
     * @return array
     */
    protected function _filterPostData($data)
    {
        $data = $this->_filterDates($data, array('time_published'));
        return $data;
    }

    /**
     * Grid ajax action
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Flush News Posts Images Cache action
     */
    public function flushAction()
    {
        if (Mage::helper('zimbrabilling_zmb/image')->flushImagesCache()) {
            $this->_getSession()->addSuccess('Cache successfully flushed');
        } else {
            $this->_getSession()->addError('There was error during flushing cache');
        }
        $this->_forward('index');
    }
	
	public function viewAction(){
		 $this->loadLayout();
        #$this->renderLayout();
    	$model = Mage::getModel('zimbrabilling_zmb/invoices');
		#echo count($model);
        $invoiceId = $this->getRequest()->getParam('id');
		#echo $invoiceId;
		
        if ($invoiceId) { 
            $a = $model->load($invoiceId);
           # echo '<pre>';print_r($a);
			// prepare title
            $this->_title($model['id']);
			#echo $model['id'];
            $breadCrumb = Mage::helper('zimbrabilling_zmb')->__('Edit Item');
			  
        }
		 // Init breadcrumbs
        $this->_initAction()->_addBreadcrumb($breadCrumb, $breadCrumb);
		
        
        //  render layout
        $this->renderLayout();
	}
	
	 public function sendmailAction(){
			
	        # die('hello');
			 $customerid = $_POST['id'];
			 $invoiceId = $_POST['invoiceid'];
		   #die;
		   $customer = Mage::getModel('customer/customer')->load($customerid);
		   $read = Mage::getSingleton('core/resource')->getConnection('core_read');
		   $invoicedetails = $read->query("SELECT * FROM `zimbrabilling_invoices` WHERE id = '".$invoiceId."'")->fetchObject();
		   $customerdata = $customer->getData();
		   $customername = $customerdata['firstname'].'&nbsp;'.$customerdata['lastname'];
		
		   /*$message  ="<p>Domain Information</p>";
		   $message .= "<p><b>Name:</b>".$customerdata['first_name']."</p>";
		   $message .= "<p><b>Email:</b>".$customerdata['email']."</p>";*/
		   
		   $message  ="";
		   $message .= '<body style="background:#F6F6F6; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">
<div style="background:#F6F6F6; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; margin:0; padding:0;">
<table cellspacing="0" cellpadding="0" border="0" height="100%" width="100%">
<tr>
    <td align="center" valign="top" style="padding:20px 0 20px 0">
        <!-- [ header starts here] -->
        <table bgcolor="#FFFFFF" cellspacing="0" cellpadding="10" border="0" width="650" style="border:1px solid #E0E0E0;">
            
        <!-- [ middle starts here] -->
            <tr>
                <td valign="top">
                    <h1 style="font-size:22px; font-weight:normal; line-height:22px; margin:0 0 11px 0;"">Hello, '.$customername.'</h1>
                    <p style="font-size:12px; line-height:16px; margin:0;">
                        Thank you for your order from Main website store.
                        
                    </p>
                </td>
            </tr>
            <tr>
                <td>
                    <h2 style="font-size:18px; font-weight:normal; margin:0;">Your Invoice #'.$invoicedetails->id.'</h2>
                </td>
            </tr>
            <tr>
                <td>
                    <table cellspacing="0" cellpadding="0" border="0" width="650">
                        <thead>
                            <tr>
                                <th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px; padding:5px 9px 6px 9px; line-height:1em;">Shipping Information:</th>
                                <th width="10"></th>
                                <th align="left" width="325" bgcolor="#EAEAEA" style="font-size:13px; padding:5px 9px 6px 9px; line-height:1em;">Payment Method:</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td valign="top" style="font-size:12px; padding:7px 9px 9px 9px; border-left:1px solid #EAEAEA; border-bottom:1px solid #EAEAEA; border-right:1px solid #EAEAEA;">'
                              .$invoicedetails->name.'<br/>'.$invoicedetails->address_street.'<br/>'.$invoicedetails->address_city.','.$invoicedetails->address_state.'&nbsp;'.$invoicedetails->address_zip.'<br/>'.$invoicedetails->address_country.
                            '</td>
                            <td>&nbsp;</td>
                            <td valign="top" style="font-size:12px; padding:7px 9px 9px 9px; border-left:1px solid #EAEAEA; border-bottom:1px solid #EAEAEA; border-right:1px solid #EAEAEA;">
                                PayPal Express Checkout <br/>
								Payer Email <br/>'.$invoicedetails->payer_email.'</td>
                        </tr>
                        </tbody>
                    </table>
              
                </td>
            </tr>
			<tr>
             <td>
               <table width="650" cellspacing="0" cellpadding="0" border="0" style="border:1px solid #eaeaea">
                 <thead>
                   <tr>
                     <th bgcolor="#EAEAEA" align="left" style="font-size:13px;padding:3px 9px">Item</th>
                     <th bgcolor="#EAEAEA" align="left" style="font-size:13px;padding:3px 9px"></th>
                     <th bgcolor="#EAEAEA" align="center" style="font-size:13px;padding:3px 9px">Qty</th>
                     <th bgcolor="#EAEAEA" align="right" style="font-size:13px;padding:3px 9px">Subtotal</th>
                   </tr>
                 </thead>
                 <tbody bgcolor="#F6F6F6">
                   <tr>
                     <td valign="top" align="left" style="padding:3px 9px;border-bottom:1px dotted #cccccc"><strong style="font-size:11px">'.$invoicedetails->item_name.'</strong></td>
                     <td valign="top" align="left" style="font-size:11px;padding:3px 9px;border-bottom:1px dotted #cccccc"></td>
                     <td valign="top" align="center" style="font-size:11px;padding:3px 9px;border-bottom:1px dotted #cccccc">1</td>
                     <td valign="top" align="right" style="font-size:11px;padding:3px 9px;border-bottom:1px dotted #cccccc"><span>$'.$invoicedetails->payment_gross.'</span></td>
                   </tr>
                 </tbody>
                 <tfoot>
                   <tr>
                     <td align="right" style="padding:3px 9px" colspan="3"> Subtotal </td>
                     <td align="right" style="padding:3px 9px"><span>$'.$invoicedetails->payment_gross.'</span></td>
                   </tr>
                   <tr>
                     <td align="right" style="padding:3px 9px" colspan="3"> Shipping &amp; Handling </td>
                     <td align="right" style="padding:3px 9px"><span>$'.$invoicedetails->handling_amount.'</span></td>
                   </tr>
                   <tr>
                     <td align="right" style="padding:3px 9px" colspan="3"><strong>Grand Total</strong></td>
                     <td align="right" style="padding:3px 9px"><strong><span>$'.$invoicedetails->payment_gross.'</span></strong></td>
                   </tr>
                 </tfoot>
               </table>
             </td>
            </tr>
            <tr>
                <td bgcolor="#EAEAEA" align="center" style="background:#EAEAEA; text-align:center;"><center><p style="font-size:12px; margin:0;">Thank you again, <strong>Main Website Store</strong></strong></p></center></td>
            </tr>
        </table>
    </td>
</tr>
</table>
</div>
</body>
';
		   
		   $mail = Mage::getModel('core/email');
		   $mail->setToName($customerdata['firstname'].' '.$customerdata['lastname']);
		   $mail->setToEmail($customerdata['email']);
		   $mail->setBody($message);
		   $mail->setSubject('Main Website Store');
		   $mail->setFromName("Sales");
		   $mail->setType('html');// YOu can use Html or text as Mail format
		   $mail->send();
		   echo 'Mail sent';
			
			
 }
}