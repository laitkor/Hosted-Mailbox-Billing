<?php
/**
 * News List admin edit form main tab
 *
 * @author Magento
 */
class Zimbrabilling_Zmb_Block_Adminhtml_Zmb_Edit_Tab_Main
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Prepare form elements for tab
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {  
        $model = Mage::helper('zimbrabilling_zmb')->getZmbItemInstance();

        /**
         * Checking if user have permissions to save information
         */
        if (Mage::helper('zimbrabilling_zmb/admin')->isActionAllowed('save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }

        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('zmb_main_');

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('zimbrabilling_zmb')->__('Plans Info')
        ));

        if ($model->getId()) {
            $fieldset->addField('zmb_id', 'hidden', array(
                'name' => 'zmb_id',
            ));
        }

        $fieldset->addField('title', 'text', array(
            'name'     => 'title',
            'label'    => Mage::helper('zimbrabilling_zmb')->__('Plan Title'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__('Plan Title'),
            'required' => true,
            'disabled' => $isElementDisabled
        ));

        $fieldset->addField('author', 'text', array(
            'name'     => 'author',
            'label'    => Mage::helper('zimbrabilling_zmb')->__('Author'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__('Author'),
            'required' => true,
            'disabled' => $isElementDisabled
        ));

        $fieldset->addField('published_at', 'date', array(
            'name'     => 'published_at',
            'format'   => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            'image'    => $this->getSkinUrl('images/grid-cal.gif'),
            'label'    => Mage::helper('zimbrabilling_zmb')->__('Publishing Date'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__('Publishing Date'),
            'required' => true
        ));
		
		$fieldset->addField('mailboxes', 'text', array(
            'name'     => 'mailboxes',
            'label'    => Mage::helper('zimbrabilling_zmb')->__('Mailboxes'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__('Mailboxes'),
            'required' => true
        ));
		
		$fieldset->addField('mailboxsize', 'text', array(
            'name'     => 'mailboxsize',
            'label'    => Mage::helper('zimbrabilling_zmb')->__('Mailbox Size'),
            'title'    => Mage::helper('zimbrabilling_zmb')->__('Mailbox Size'),
            'required' => true
        ));
		


        Mage::dispatchEvent('adminhtml_zmb_edit_tab_main_prepare_form', array('form' => $form));

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('zimbrabilling_zmb')->__('Plans Info');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('zimbrabilling_zmb')->__('Plans Info');
    }

    /**
     * Returns status flag about this tab can be shown or not
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
}
