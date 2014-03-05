<?php

class Zimbrabilling_Zmb_Adminhtml_ZmbController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        $this->loadLayout()
            ->_setActiveMenu('zmb/manage')
            ->_addBreadcrumb(
                  Mage::helper('zimbrabilling_zmb')->__('Zmb'),
                  Mage::helper('zimbrabilling_zmb')->__('Zmb')
              )
            ->_addBreadcrumb(
                  Mage::helper('zimbrabilling_zmb')->__('Manage Plans'),
                  Mage::helper('zimbrabilling_zmb')->__('Manage Plans')
              )
        ;
        return $this;
    }
    public function indexAction()
    {
        $this->_title($this->__('Zimbra Plans'))
             ->_title($this->__('Manage Plans'));

        $this->_initAction();
        $this->renderLayout();
    }

	public function editAction() {
        $this->_title($this->__('Zimbra Plans'))
             ->_title($this->__('Manage Plans'));

        // 1. instance news model
        /* @var $model Magentostudy_News_Model_Item */
        $model = Mage::getModel('zimbrabilling_zmb/zmb');

        // 2. if exists id, check it and load data
        $newsId = $this->getRequest()->getParam('id');
        if ($newsId) {
            $model->load($newsId);

            if (!$model->getId()) {
                $this->_getSession()->addError(
                    Mage::helper('zimbrabilling_zmb')->__('News item does not exist.')
                );
                return $this->_redirect('*/*/');
            }
            // prepare title
            $this->_title($model->getTitle());
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
				#$delete = "DELETE FROM zimbrabilling_cycles WHERE planid = '".$newsId."'";
				#$write->query($delete);
				#print_r($data['billingcycle_id']); die;
				#echo count($data['billingcycle_title']);
				for($i=0;$i<count($data['billingcycle_title']);$i++){
				  #$sql = "INSERT INTO zimbrabilling_cycles(planid,title,price,duration)VALUES('".$lastInsertId."','".$data['billingcycle_title'][$i]."','".$data['billingcycle_price'][$i]."','".$data['billingcycle_details'][$i]."')";
				  $sqlupdate = "UPDATE zimbrabilling_cycles SET title = '".$data['billingcycle_title'][$i]."', price = '".$data['billingcycle_price'][$i]."',duration = '".$data['billingcycle_details'][$i]."', per_mailbox_price = '".$data['billingcycle_mailboxprice'][$i]."' WHERE id = '".$data['billingcycle_id'][$i]."' ";
				 #echo $sqlupdate.'<br/>';die;
				 #$sql1 = "INSERT INTO zimbrabilling_cycles(planid,title,price,duration)VALUES('".$newsId."','".$data['billingcycle_title'][$i]."','".$data['billingcycle_price'][$i]."','".$data['billingcycle_details'][$i]."')";
				  #echo $sql1.'<br/>';
				  $write->query($sqlupdate);
				 
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
			 
			/************adding the plan in product table also****************/
			
			 /* error_reporting(E_ALL | E_STRICT);
    		  $mageFilename = 'app/Mage.php';
			  require_once $mageFilename;
			  $app = Mage::app('default');
			   
			  ini_set('display_errors', 1);
		   
		   
			  $api = new Mage_Catalog_Model_Product_Api();
			   
			  $attribute_api = new Mage_Catalog_Model_Product_Attribute_Set_Api();
			  $attribute_sets = $attribute_api->items();
			   
			  $productData = array();
			  #$productData['website_ids'] = array(1);
			  $productData['categories'] = array('Plans');
		   
			  $productData['status'] = 1;
			   
			  $productData['name'] = $data['title'];
			  $productData['description'] = $data['content'];
			  $productData['short_description'] = $data['content'];
			   
			  $productData['price'] = 12.34;
			  $productData['weight'] = 23.25;
			  $productData['tax_class_id'] =2;
			  $productData['page_layout'] ='two_columns_left';
				   
			  $new_product_id = $api->create('simple',$attribute_sets[0]['set_id'],'ND10',$productData);
			   
			  #print_r($new_product_id);
			   
			  $stockItem = Mage::getModel('cataloginventory/stock_item');
			  $stockItem->loadByProduct( $new_product_id );
			   
			  $stockItem->setData('use_config_manage_stock', 1);
			  $stockItem->setData('qty', 100);
			  $stockItem->setData('min_qty', 0);
			  $stockItem->setData('use_config_min_qty', 1);
			  $stockItem->setData('min_sale_qty', 0);
			  $stockItem->setData('use_config_max_sale_qty', 1);
			  $stockItem->setData('max_sale_qty', 0);
			  $stockItem->setData('use_config_max_sale_qty', 1);
			  $stockItem->setData('is_qty_decimal', 0);
			  $stockItem->setData('backorders', 0);
			  $stockItem->setData('notify_stock_qty', 0);
			  $stockItem->setData('is_in_stock', 1);
			  $stockItem->setData('tax_class_id', 0);
			   
			  $stockItem->save();
			   
			  $product = Mage::getModel('catalog/product')->load($new_product_id);
			   
			  #$product->setMediaGallery (array('images'=>array (), 'values'=>array ()));
			  #$product->addImageToMediaGallery ('E:/High Res Images/High Res Images/GC00012.jpg', array ('image','small_image','thumbnail'), false, false);
			  #$product->addImageToMediaGallery ('E:/High Res Images/High Res Images/GC00014_1.jpg', array ('image','small_image','thumbnail'), false, false);
			  Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
		   
			  $product->save();*/
			   
			   
/*			  $product = Mage::getModel('catalog/product')->load($new_product_id);
			  $optionData =   array(
								  "title" => "Custom Text Field Option Title 1",
								  "type" => "field",
								  "is_require" => 1,
								  "sort_order" => 1,
								  "price" => 0,
									  "price_type" => "fixed",
									  "sku" => "",
									  "max_characters" => 15
							  );
			   
			  $product->setHasOptions(1);
			  $option = Mage::getModel('catalog/product_option')
						->setProductId($new_product_id)
						->setStoreId(1)
						->addData($optionData);
			  $option->save();
			  $product->addOption($option);
			   
			  Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
		   
			  $product->save();*/
			   
/*			  $product = Mage::getModel('catalog/product')->load($new_product_id);
			  $optionData =   array(
								  "title" => "Custom Text Field Option Title 2",
								  "type" => "field",
								  "is_require" => 1,
								  "sort_order" => 2,
								  "price" => 0,
									  "price_type" => "fixed",
									  "sku" => "",
									  "max_characters" => 25
							  );
			   
			  $product->setHasOptions(1);
			  $option = Mage::getModel('catalog/product_option')
						->setProductId($new_product_id)
						->setStoreId(1)
						->addData($optionData);
			  $option->save();
			  $product->addOption($option);
			   
			  Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
		   
			  $product->save();*/
			
			
			
			/**************code for adding in product table ends here*********************/
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
                return Mage::getSingleton('admin/session')->isAllowed('zmb/manage/save');
                break;
            case 'delete':
                return Mage::getSingleton('admin/session')->isAllowed('zmb/manage/delete');
                break;
            default:
                return Mage::getSingleton('admin/session')->isAllowed('zmb/manage');
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
}