<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $subaruForestor = '2002860090';
        $car = '2010900249';
        
        $usedVehicleAPI = new Application_Model_Api_Blackbook_UsedVehicle();
        $vehicle = $usedVehicleAPI->lookupByUvc($car);
        print_r($vehicle);
        $this->view->vehicle = $vehicle;
    }


}

