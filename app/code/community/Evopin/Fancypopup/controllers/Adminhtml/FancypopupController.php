<?php

class Evopin_Fancypopup_Adminhtml_FancypopupController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
			
		
		$this->loadLayout()
			->_setActiveMenu('cms/fancypopup')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('fancypopup/fancypopup')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('fancypopup_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('cms/fancypopup');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('fancypopup/adminhtml_fancypopup_edit'))
				->_addLeft($this->getLayout()->createBlock('fancypopup/adminhtml_fancypopup_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('fancypopup')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
		
		if(isset($_FILES['image_link']['name']) and (file_exists($_FILES['image_link']['tmp_name']))) {
  			try {
    			$uploader = new Varien_File_Uploader('image_link');
    			$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
    			$uploader->setAllowRenameFiles(true);
    			$uploader->setFilesDispersion(false);
    			$path = Mage::getBaseDir('media') . DS .'evopin/fancypopup';
    			$uploader->save($path, $_FILES['image_link']['name']);
    			$name_image_link = $uploader->getUploadedFileName();
    			$data['image_link'] = 'evopin/fancypopup/' . $name_image_link;
    			//em cima acrescentei para ver se grava com path correcta
  				}catch(Exception $e) {
  			}
		}
      	else {

        	if(isset($data['image_link']['delete']) && $data['image_link']['delete'] == 1)
            	$data['image_link'] = '';
          	else
              	unset($data['image_link']);                    	
      	}
      		
	  				  			
			$model = Mage::getModel('fancypopup/fancypopup');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {	
				
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('fancypopup')->__('The fancy popup has been saved.'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('fancypopup')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('fancypopup/fancypopup');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $fancypopupsIds = $this->getRequest()->getParam('fancypopup');
        if(!is_array($fancypopupsIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($fancypopupsIds as $fancypopupId) {
                    $fancypopups = Mage::getModel('fancypopup/fancypopup')->load($fancypopupId);
                    $fancypopups->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($fancypopupsIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $fancypopupsIds = $this->getRequest()->getParam('fancypopup');
        if(!is_array($fancypopupsIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($fancypopupsIds as $fancypopupId) {
                    $fancypopups = Mage::getSingleton('fancypopup/fancypopup')
                        ->load($fancypopupId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($fancypopupsIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'fancypopup.csv';
        $content    = $this->getLayout()->createBlock('fancypopup/adminhtml_fancypopup_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'fancypopup.xml';
        $content    = $this->getLayout()->createBlock('fancypopup/adminhtml_fancypopup_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('admin/cms/fancypopup'); //ver isto muito bem
    }
    
    
}