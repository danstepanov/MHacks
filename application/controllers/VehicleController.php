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

        if(is_array($vehicle['external_colors']))
        {
            foreach($vehicle['external_colors'] as $k=>$color)
            {
                $term = $vehicle['display_title'] . " " . $color['description'];
                $a = new Application_Model_Api_Bing_Bing($term);
                $vehicle['external_colors'][$k]['url'] = $a->getImageUrl();
            }
        }
        if($vehicle['photo'] == '/images/display/fill.jpg') {
            $term = $vehicle['display_title'];
            $a = new Application_Model_Api_Bing_Bing($term);
            $vehicle['photo'] = $a->getImageUrl();
        }

        $vehicleVinAPI = new Application_Model_Api_Blackbook_VehicleVin($vehicle['full_vin']);
        $configuration = $vehicleVinAPI->getConfigurationData();
        
        $maintenanceAPI = new Application_Model_Api_Motor_Maintenance($configuration['VehicleConfigurationID']);
        $this->view->maintenanceData = $maintenanceAPI->getData();
        
        $articleSearch = new Application_Model_Api_Cad_Article();
        $cursor = $articleSearch->getArticleByMake($vehicle['make']);
        
        $vehicle['articles'] = $cursor;
        if(is_array($vehicle['articles']))
            shuffle($vehicle['articles']);

        $vehicleByNameAPI = new Application_Model_Api_Cad_VehicleByName($vehicle['model']);
        $model = $vehicleByNameAPI->getModelID();
        
        $performanceDataAPI = new Application_Model_Api_Cad_PerformanceData($model, $vehicle['full_year']);
        $this->view->perfdata = $performanceDataAPI->lookup();
                
        $this->view->model = $model;
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

    public function ajaxSetupAction()
    {
        $milesSince = $this->_getParam("miles-since");
        $monthsSince = $this->_getParam("months-since");
        $milesPer = $this->_getParam("miles-per");
        $email = $this->_getParam("email");
        $monthsIn = $this->_getParam("monthIn");
        $milesIn = $this->_getParam("milesIn");
        $title = strtolower($this->_getParam("title"));
        $effort = strtolower($this->_getParam("effort"));
        $frequency = strtolower($this->_getParam("frequency"));

        $res = array();
        if($milesSince != null && $monthsSince != null && $milesPer != null 
            && $email != null && $monthsIn != null && $milesIn != null)
        {
            $daysUntil = -1;
            if($monthsIn > 0) {
                $monthsDiff = $monthsIn - $monthsSince;
                $daysUntil = 30 * $monthsDiff;
            } else if($milesIn > 0) {
                $milesDiff = $milesIn - $milesSince;
                $daysUntil = 7 * floor($milesDiff / $milesPer);
            }
            //give 2 weeks out
            $daysUntil -= 7 * 2;
            $daysUntil = max(1, $daysUntil);

            $date = new DateTime("today +$daysUntil days");

            $emailBody = "Dear Car Owner, you have a car maintenance item coming up in the near future. You need to get the following " .
                "service inspected: $title. This service should take $effort and needs to occur $frequency. Drive safe!";

            $emailModel = new Application_Model_DbTable_Emails();
            $data = array(
                'when' => $date->format('Y-m-d H:i:s'),
                'body' => $emailBody,
                'to' => $email
            );

            $emailModel->insert($data);
            $res['status'] = 200;
            $res['message'] = "You will receive a message in " . $daysUntil . " days!";
        }
        else
        {
            $res['status'] = 500;
            $res['message'] = "Please fill out all fields!";
        }
        echo json_encode($res);
        die();
    }    
}

