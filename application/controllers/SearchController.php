<?php

class SearchController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $searchModel = new Application_Model_DbTable_SearchMap();
        
        $this->view->makes = $searchModel->getMakes();
    }

    public function ajaxYearAction()
    {
        $searchModel = new Application_Model_DbTable_SearchMap();
        $make = $this->_getParam('make');
        $model = $this->_getParam('model');
        $years = $searchModel->getYearFromMakeModel($make, $model);
        
        die(json_encode($years));
    }
    
    public function ajaxModelAction()
    {
        $searchModel = new Application_Model_DbTable_SearchMap();
        $make = $this->_getParam('make');
        $models = $searchModel->getModelFromMake($make);
        
        die(json_encode($models));
    }
    
    public function ajaxStyleAction()
    {
        $searchModel = new Application_Model_DbTable_SearchMap();
        $make = $this->_getParam('make');
        $model = $this->_getParam('model');
        $year = $this->_getParam('year');
        
        $styles = $searchModel->getStyles($make, $model, $year);
        
        die(json_encode($styles));
    }
    
    public function ajaxUvcAction()
    {
        $searchModel = new Application_Model_DbTable_SearchMap();
        $make = $this->_getParam('make');
        $model = $this->_getParam('model');
        $year = $this->_getParam('year');
        $style = $this->_getParam('style');
        
        $uvc = $searchModel->getUVC($make, $model, $year, $style);
        
        die(json_encode($uvc));
    }

    public function bingAction()
    {
        $term = $this->_getParam("term");
        if($term)
        {
            $a = new Application_Model_Api_Bing_Bing($term);
            echo $a->getImageUrl();
        }
        die();
    }
}

