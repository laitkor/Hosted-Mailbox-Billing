<?php
/**
 * News List admin edit form image tab
 *
 * @author Magento
 */
class Zimbrabilling_Zmb_Block_Adminhtml_Zmb_Edit_Tab_Custom
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Prepare form elements
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        /**
         * Checking if user have permissions to save information
         */
        if (Mage::helper('zimbrabilling_zmb/admin')->isActionAllowed('save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }

        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('zmb_custom_');

        $model = Mage::helper('zimbrabilling_zmb')->getZmbItemInstance();
        $billing = Mage::helper('zimbrabilling_zmb')->getBillingItemInstance();
		
		$billing_cycles_count = Mage::getStoreConfig('zmb/view/no_of_billingcycles');
    	#echo $billing_cycles_count;
		if($billing_cycles_count =='' || $billing_cycles_count <= 0){
			 
			 $cyclescount = '4';
			 #echo '<div id="messages"><ul class="messages"><li class="error-msg"><ul><li><span>Please enter "Set Number of billingcycles" in System>Configuration>ZMB</span></li></ul></li></ul></div>';
		}
		else{ 
		    $cyclescount = $billing_cycles_count;
		}
			$fieldset = $form->addFieldset('custom_fieldset', array(
				'legend'    => Mage::helper('zimbrabilling_zmb')->__('Add Billing Cycles'), 'class' => 'fieldset'
			));
	
			$this->_addElementTypes($fieldset);
			
			
			
			for($i=1; $i<=$cyclescount;$i++)
			{
		   $fieldset->addField('billingcycle'.$i, 'label', array(
				'name'     => 'billingcycle'.$i,
				'label'    => Mage::helper('zimbrabilling_zmb')->__('Billing Cycle'.$i),
				'title'    => Mage::helper('zimbrabilling_zmb')->__('Billing Cycle'.$i),
			));
			
			$fieldset->addField('billingcycle_title_'.$i, 'text', array(
				'name'     => 'billingcycle_title[]',
				'label'    => Mage::helper('core')->__('Title'),
				'title'    => Mage::helper('core')->__('Title'),
				'required' => true
			));
			
			$fieldset->addField('billingcycle_price_'.$i, 'text', array(
				'name'     => 'billingcycle_price[]',
				'label'    => Mage::helper('zimbrabilling_zmb')->__(' Price'),
				'title'    => Mage::helper('zimbrabilling_zmb')->__(' Price'),
				'required' => true
			));
			
			$fieldset->addField('billingcycle_details_'.$i, 'text', array(
				'name'     => 'billingcycle_details[]',
				'label'    => Mage::helper('zimbrabilling_zmb')->__('Details'),
				'title'    => Mage::helper('zimbrabilling_zmb')->__('Details'),
				'required' => true
			));
			
			$fieldset->addField('billingcycle_mailboxprice_'.$i, 'text', array(
				'name'     => 'billingcycle_mailboxprice[]',
				'label'    => Mage::helper('zimbrabilling_zmb')->__('Per Mailbox Price'),
				'title'    => Mage::helper('zimbrabilling_zmb')->__('Per Mailbox Price'),
				'required' => true
			));
			
			$fieldset->addField('billingcycle_id_'.$i, 'hidden', array(
				'name'     => 'billingcycle_id[]',
			));
			
			}
		
		/*$fieldset->addField('billingcycle2', 'label', array(
            'name'     => 'billingcycle2',
            'label'    => Mage::helper('zimbrabilling_zmb')->__('Billing Cycle2'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__('Billing Cycle2'),
        ));
		
		$fieldset->addField('billingcycle_title_2', 'text', array(
            'name'     => 'billingcycle_title[]',
            'label'    => Mage::helper('zimbrabilling_zmb')->__('Title'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__('Title'),
			'required' => true
        ));
		
		$fieldset->addField('billingcycle_price_2', 'text', array(
            'name'     => 'billingcycle_price[]',
            'label'    => Mage::helper('zimbrabilling_zmb')->__(' Price'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__(' Price'),
			'required' => true
        ));
		
		$fieldset->addField('billingcycle_details_2', 'text', array(
            'name'     => 'billingcycle_details[]',
            'label'    => Mage::helper('zimbrabilling_zmb')->__('Details'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__('Details'),
			'required' => true
        ));
		
		$fieldset->addField('billingcycle3', 'label', array(
            'name'     => 'billingcycle3',
            'label'    => Mage::helper('zimbrabilling_zmb')->__('Billing Cycle3'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__('Billing Cycle3'),
        ));
		
		$fieldset->addField('billingcycle_title_3', 'text', array(
            'name'     => 'billingcycle_title[]',
            'label'    => Mage::helper('zimbrabilling_zmb')->__('Title'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__('Title'),
			'required' => true
        ));
		
		$fieldset->addField('billingcycle_price_3', 'text', array(
            'name'     => 'billingcycle_price[]',
            'label'    => Mage::helper('zimbrabilling_zmb')->__(' Price'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__(' Price'),
			'required' => true
        ));
		
		$fieldset->addField('billingcycle_details_3', 'text', array(
            'name'     => 'billingcycle_details[]',
            'label'    => Mage::helper('zimbrabilling_zmb')->__('Details'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__('Details'),
			'required' => true
        ));
		
		$fieldset->addField('billingcycle4', 'label', array(
            'name'     => 'billingcycle4',
            'label'    => Mage::helper('zimbrabilling_zmb')->__('Billing Cycle4'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__('Billing Cycle4'),
        ));
		
		$fieldset->addField('billingcycle_title_4', 'text', array(
            'name'     => 'billingcycle_title[]',
            'label'    => Mage::helper('zimbrabilling_zmb')->__('Title'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__('Title'),
			'required' => true
        ));
		
		$fieldset->addField('billingcycle_price_4', 'text', array(
            'name'     => 'billingcycle_price[]',
            'label'    => Mage::helper('zimbrabilling_zmb')->__(' Price'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__(' Price'),
			'required' => true
        ));
		
		$fieldset->addField('billingcycle_details_4', 'text', array(
            'name'     => 'billingcycle_details[]',
            'label'    => Mage::helper('zimbrabilling_zmb')->__('Details'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__('Details'),
			'required' => true
        ));*/

        Mage::dispatchEvent('adminhtml_zmb_edit_tab_custom_prepare_form', array('form' => $form));
       # $model->getId();
        #$form->setValues($model->getData());
       # $this->setForm($form);
		$arr = array();
		#echo '<pre>'; print_r($billing);
		foreach($billing as $k=>$b){ #echo 'test';
			$inc_id = $k+1;
			$bt = 'billingcycle_title_'.$inc_id;
			$bp = 'billingcycle_price_'.$inc_id;
			$bd = 'billingcycle_details_'.$inc_id;
			$bmp = 'billingcycle_mailboxprice_'.$inc_id;
			$bi = 'billingcycle_id_'.$inc_id;
			$arr[$bt] = $b['title'];
			$arr[$bp] = $b['price'];
			$arr[$bd] = $b['duration'];
			$arr[$bmp] = $b['per_mailbox_price'];
			$arr[$bi] = $b['id'];
			#echo '<pre>';print_r($arr);
			#$form->setValues($arr);
        	#$this->setForm($form);
		}
		$form->setValues($arr);
		$this->setForm($form);
		
		
/*		echo '<pre>';
		print_r($model->getData());
		echo '</pre>';
		echo '<pre>';
        print_r($billing);*/
		 
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('zimbrabilling_zmb')->__('Custom Options');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('zimbrabilling_zmb')->__('Custom Options');
    }

    /**
     * Returns status flag about this tab can be showen or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Retrieve predefined additional element types
     *
     * @return array
     */
     protected function _getAdditionalElementTypes()
     {
         return array(
            'image' => Mage::getConfig()->getBlockClassName('zimbrabilling_zmb/adminhtml_zmb_edit_form_element_custom')
        );
     }
}