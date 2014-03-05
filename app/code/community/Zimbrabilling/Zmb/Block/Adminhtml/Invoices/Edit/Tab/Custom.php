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

        $fieldset = $form->addFieldset('custom_fieldset', array(
            'legend'    => Mage::helper('zimbrabilling_zmb')->__('Add Billing Cycles'), 'class' => 'fieldset'
        ));

        $this->_addElementTypes($fieldset);
        


		
	   $fieldset->addField('billingcycle1', 'label', array(
            'name'     => 'billingcycle1',
            'label'    => Mage::helper('zimbrabilling_zmb')->__('Billing Cycle1'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__('Billing Cycle1'),
        ));
		
		$fieldset->addField('Billing Cycle Title1', 'text', array(
            'name'     => 'billingcycle_title[]',
            'label'    => Mage::helper('zimbrabilling_zmb')->__('Title'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__('Title'),
			'required' => true
        ));
		
		$fieldset->addField('Billing Cycle Price1', 'text', array(
            'name'     => 'billingcycle_price[]',
            'label'    => Mage::helper('zimbrabilling_zmb')->__(' Price'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__(' Price'),
			'required' => true
        ));
		
		$fieldset->addField('Billing Cycle Details1', 'text', array(
            'name'     => 'billingcycle_details[]',
            'label'    => Mage::helper('zimbrabilling_zmb')->__('Details'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__('Details'),
			'required' => true
        ));
		
		
		$fieldset->addField('billingcycle2', 'label', array(
            'name'     => 'billingcycle2',
            'label'    => Mage::helper('zimbrabilling_zmb')->__('Billing Cycle2'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__('Billing Cycle2'),
        ));
		
		$fieldset->addField('Billing Cycle Title2', 'text', array(
            'name'     => 'billingcycle_title[]',
            'label'    => Mage::helper('zimbrabilling_zmb')->__('Title'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__('Title'),
			'required' => true
        ));
		
		$fieldset->addField('Billing Cycle Price2', 'text', array(
            'name'     => 'billingcycle_price[]',
            'label'    => Mage::helper('zimbrabilling_zmb')->__(' Price'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__(' Price'),
			'required' => true
        ));
		
		$fieldset->addField('Billing Cycle Details2', 'text', array(
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
		
		$fieldset->addField('Billing Cycle Title3', 'text', array(
            'name'     => 'billingcycle_title[]',
            'label'    => Mage::helper('zimbrabilling_zmb')->__('Title'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__('Title'),
			'required' => true
        ));
		
		$fieldset->addField('Billing Cycle Price3', 'text', array(
            'name'     => 'billingcycle_price[]',
            'label'    => Mage::helper('zimbrabilling_zmb')->__(' Price'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__(' Price'),
			'required' => true
        ));
		
		$fieldset->addField('Billing Cycle Details3', 'text', array(
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
		
		$fieldset->addField('Billing Cycle Title4', 'text', array(
            'name'     => 'billingcycle_title[]',
            'label'    => Mage::helper('zimbrabilling_zmb')->__('Title'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__('Title'),
			'required' => true
        ));
		
		$fieldset->addField('Billing Cycle Price4', 'text', array(
            'name'     => 'billingcycle_price[]',
            'label'    => Mage::helper('zimbrabilling_zmb')->__(' Price'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__(' Price'),
			'required' => true
        ));
		
		$fieldset->addField('Billing Cycle Details4', 'text', array(
            'name'     => 'billingcycle_details[]',
            'label'    => Mage::helper('zimbrabilling_zmb')->__('Details'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__('Details'),
			'required' => true
        ));

        Mage::dispatchEvent('adminhtml_zmb_edit_tab_custom_prepare_form', array('form' => $form));
       # $model->getId();
        $form->setValues($model->getData());
        $this->setForm($form);
		$arr = array();
		foreach($billing as $b){ #echo 'test';
			$arr['title'] = 'title';
			#print_r($arr);
			$form->setValues($arr);
        	$this->setForm($form);
		}
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