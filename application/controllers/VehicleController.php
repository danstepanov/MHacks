<?php

class VehicleController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }
    
    public function indexAction()
    {
        $subaruForestor = '2002860090';
        $car = '2010900249';
        
        $uvc = $this->_getParam('uvc', $subaruForestor);
        
        $usedVehicleAPI = new Application_Model_Api_Blackbook_UsedVehicle();
        $vehicle = $usedVehicleAPI->lookupByUvc($uvc);

        $articleSearch = new Application_Model_Api_Cad_Article();
        $cursor = $articleSearch->getArticleByMake($vehicle['make']);
        
        $vehicle['articles'] = iterator_to_array($cursor);
        shuffle($vehicle['articles']);
        $this->view->vehicle = $vehicle;
    }

    public function seedAction()
    {
    	$seeder = new Application_Model_Api_Cad_ArticleSeed();
    	$seeder->seedArticles();
    	die();
    }
    
    public function servepdfAction()
    {
    	$this->getResponse()->setHeader('content-type', 'application-pdf');
    	$this->getResponse()->sendHeaders();
    
    	$uvc = $this->_getParam('uvc');
    
    	$pdfAPI = new Application_Model_Api_Blackbook_PDFSpec();
    	$pdfAPI->lookupByUvc($uvc);
    
    	$this->redirect('/pdf/' . $uvc . '.pdf');
    }
}

