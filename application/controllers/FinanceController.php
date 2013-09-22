<?php

class FinanceController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $financeModel = new Application_Model_Api_Cad_Finance();
        
        $zip = $this->_getParam('zipcode', '10591');
        $model = $this->_getParam('model', '126');
        $lease = $this->_getParam('lease', 'Lease');
        $months = $this->_getParam('months', '24');
        $downpay = $this->_getParam('downpay', '0');
        $credit = $this->_getParam('credit', '600');
        
        $parse = $financeModel->lookup($zip, $model, $lease, $months, $downpay, $credit);
        echo json_encode($parse);
        die();
    }


}

