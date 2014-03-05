<?php

class Zimbrabilling_Zmb_Block_Adminhtml_Zmb_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
        parent::__construct();
        $this->setId('zmb_list_grid');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
		
  }

  protected function _prepareCollection()
  { 
       $collection = Mage::getModel('zimbrabilling_zmb/zmb')->getResourceCollection();
	   #print_r($collection);
	   #echo count($collection);
       $this->setCollection($collection);
		#echo 'test';
	    
        return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {    
       #echo 'test';
       $this->addColumn('zmb_id', array(
            'header'    => Mage::helper('zimbrabilling_zmb')->__('ID'),
            'width'     => '50px',
			'filter'    => false,
            'sortable'  => false,
            'index'     => 'zmb_id',
        ));

        $this->addColumn('title', array(
            'header'    => Mage::helper('zimbrabilling_zmb')->__('News Title'),
			'filter'    => false,
            'sortable'  => false,
            'index'     => 'title',
        ));

        $this->addColumn('author', array(
            'header'    => Mage::helper('zimbrabilling_zmb')->__('Author'),
			'filter'    => false,
            'sortable'  => false,
            'index'     => 'author',
        ));


        $this->addColumn('created_at', array(
            'header'   => Mage::helper('zimbrabilling_zmb')->__('Created'),
            'sortable' => false,
			'filter'   => false,	
            'width'    => '170px',
            'index'    => 'created_at',
            'type'     => 'datetime',
        ));

        $this->addColumn('action',
            array(
                'header'    => Mage::helper('zimbrabilling_zmb')->__('Action'),
                'width'     => '100px',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(array(
                    'caption' => Mage::helper('zimbrabilling_zmb')->__('Edit'),
                    'url'     => array('base' => '*/*/edit'),
                    'field'   => 'id'
                )),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'zmb',
        ));
        
		 #$this->addExportType('*/exportCsv', Mage::helper('zimbrabilling_zmb')->__('CSV'));
 		 #$this->addExportType('*/*/exportXml', Mage::helper('zimbrabilling_zmb')->__('XML'));*/
		
        return parent::_prepareColumns();
  }

  protected function _prepareMassaction()
    {
        #$this->setMassactionIdField('zmb_id');
       # $this->getMassactionBlock()->setFormFieldName('zmb');

        

        #$statuses = Mage::getSingleton('zimbrabilling_zmb/status')->getOptionArray();

        #return $this;
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