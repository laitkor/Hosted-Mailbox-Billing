<?php
/**
 * News List admin edit form container
 *
 * @author Magento
 */
class Zimbrabilling_Zmb_Block_Adminhtml_Zmb_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Initialize edit form container
     *
     */
    public function __construct()
    {
        $this->_objectId   = 'id';
        $this->_blockGroup = 'zimbrabilling_zmb';
        $this->_controller = 'adminhtml_zmb';

        parent::__construct();

        if (Mage::helper('zimbrabilling_zmb/admin')->isActionAllowed('save')) {
            $this->_updateButton('save', 'label', Mage::helper('zimbrabilling_zmb')->__('Save Plan'));
            $this->_addButton('saveandcontinue', array(
                'label'   => Mage::helper('adminhtml')->__('Save and Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ), -100);
        } else {
            $this->_removeButton('save');
        }

        if (Mage::helper('zimbrabilling_zmb/admin')->isActionAllowed('delete')) {
            $this->_updateButton('delete', 'label', Mage::helper('zimbrabilling_zmb')->__('Delete Plan'));
        } else {
            $this->_removeButton('delete');
        }

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('page_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'page_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'page_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * Retrieve text for header element depending on loaded page
     *
     * @return string
     */
    public function getHeaderText()
    {
        $model = Mage::helper('zimbrabilling_zmb')->getZmbItemInstance();
        if ($model->getId()) {
            return Mage::helper('zimbrabilling_zmb')->__("Edit Plan '%s'",
                 $this->escapeHtml($model->getTitle()));
        } else {
            return Mage::helper('zimbrabilling_zmb')->__('New Plan');
        }
    }
}