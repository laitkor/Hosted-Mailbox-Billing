<?php

class Zimbrabilling_Zmb_Block_Adminhtml_Invoices_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
        parent::__construct();
        $this->setId('invoices_list_grid');
        $this->setDefaultSort('payment_date');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
		
		
  }

  protected function _prepareCollection()
  { 
       $collection = Mage::getModel('zimbrabilling_zmb/invoices')->getResourceCollection();
	   #echo 'test';
	   #echo '<pre>';
	   #print_r($collection);
	   #die;
	   #echo count($collection); 
	   #die;
       $this->setCollection($collection); 
       return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {    
      # echo 'testing'; die;
       $this->addColumn('id', array(
            'header'    => Mage::helper('zimbrabilling_zmb')->__('Invoice ID'),
			'filter'    => false,
            'sortable'  => false,
            'width'     => '50px',
            'index'     => 'id',
        ));

        $this->addColumn('payment_date', array(
            'header'    => Mage::helper('zimbrabilling_zmb')->__('Invoice Date'),
			'filter'    => false,
            'sortable'  => false,
            'index'     => 'payment_date',
        ));
		
		$this->addColumn('payment_gross', array(
            'header'    => Mage::helper('zimbrabilling_zmb')->__('Amount'),
			'filter'    => false,
            'sortable'  => false,
            'index'     => 'payment_gross',
        ));
		
		$this->addColumn('name', array(
            'header'    => Mage::helper('zimbrabilling_zmb')->__('Bill to name'),
			'filter'    => false,
            'sortable'  => false,
            'index'     => 'name',
        ));
		
		$this->addColumn('payment_status', array(
            'header'    => Mage::helper('zimbrabilling_zmb')->__('Status'),
			 'filter'    => false,
             'sortable'  => false,
            'index'     => 'payment_status',
        ));
		
        $this->addColumn('action',
            array(
                'header'    => Mage::helper('zimbrabilling_zmb')->__('Action'),
                'width'     => '100px',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(array(
                    'caption' => Mage::helper('zimbrabilling_zmb')->__('View'),
                    'url'     => array('base' => '*/*/view'),
                    'field'   => 'id'
                )),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'invoices',
        ));
        
		 #$this->addExportType('*/*/exportCsv', Mage::helper('zimbrabilling_zmb')->__('CSV'));
 		# $this->addExportType('*/*/exportXml', Mage::helper('zimbrabilling_zmb')->__('XML'));
		
        return parent::_prepareColumns();
  }

   public function getMainButtonsHtml()
{
    return '';
}
 
  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }
   
   
       /**
     * Grid url getter
     *
     * @return string current grid url
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }
}