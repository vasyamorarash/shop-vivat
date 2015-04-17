<?php

class Evopin_Fancypopup_Block_Adminhtml_Fancypopup_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('fancypopupGrid');
      $this->setDefaultSort('fancypopup_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('fancypopup/fancypopup')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('fancypopup_id', array(
          'header'    => Mage::helper('fancypopup')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'fancypopup_id',
      ));

      $this->addColumn('popup_name', array(
          'header'    => Mage::helper('fancypopup')->__('Name'),
          'align'     =>'left',
          'index'     => 'popup_name',
      ));               	  	  
	  

      $this->addColumn('url_link', array(
          'header'    => Mage::helper('fancypopup')->__('Link'),
          'align'     =>'left',
          'index'     => 'url_link',
      ));  
	  
      $this->addColumn('from_date', array(
          'header'    => Mage::helper('fancypopup')->__('From:'),
          'type' => 'datetime',
          'index'     => 'from_date',
      )); 
      
      $this->addColumn('to_date', array(
          'header'    => Mage::helper('fancypopup')->__('To:'),
          'type' => 'datetime',
          'index'     => 'to_date',
      ));     
	  
      $this->addColumn('is_active', array(
          'header'    => Mage::helper('fancypopup')->__('Status'),
          'options' => $this->helper('fancypopup/data')->getStatusOptions(),
          'type' => 'options',
          'index'     => 'is_active',
      ));         
	  
      if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'        => Mage::helper('fancypopup')->__('Store View'),
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => true,
                'store_view'    => true,
                'sortable'      => false,
                'filter_condition_callback'
                                => array($this, '_filterStoreCondition'),
            ));
        }

	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('fancypopup')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('fancypopup')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
	  
      return parent::_prepareColumns();
  }



   protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addStoreFilter($value);
    }






    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('fancypopup_id');
        $this->getMassactionBlock()->setFormFieldName('fancypopup');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('fancypopup')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('fancypopup')->__('Are you sure?')
        ));

        
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}