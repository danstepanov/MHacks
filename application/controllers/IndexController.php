<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    }
    
    public function importAction()
    {
        set_time_limit(120);
        $mapModel = new Application_Model_DbTable_SearchMap();
        if (($handle = fopen("test.csv", "r")) !== FALSE) {
        	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        	    if($mapModel->fetchRow("uvc = '{$data[6]}'") == null)
        	    {
            	   $data = array(
            	           'class' => $data[0],
            	           'year' => $data[1],
            	           'make' => $data[2],
            	           'model' => $data[3],
            	           'series' => $data[4],
            	           'style' => $data[5],
            	           'uvc' => $data[6]
            	   );
            	   $mapModel->insert($data);
        	    }
        	}
        }
    }

}

