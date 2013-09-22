<?php 

class Application_Model_Api_Cad_Finance extends Application_Model_Api_Abstract_API
{
    public function __construct()
    {
        $this->url = 'http://hearstcars.api.mashery.com/v1/api/payments/payments-by-model';
        $this->escape = false;
    }
    
    public function lookup($zip, $model, $lease, $months, $downpay, $credit)
    {
        //=zip/=model/New/=Lease:Loan:All/=months/=downpay/0/0/=credit/json
        $parsed = $this->execute("$zip/$model/New/$lease/$months/$downpay/0/0/$credit/json");

        $vehicles = array();
        
        $leaseInfo = $parsed['paymentdata']['payments'];
        $leaseInfo = array_shift($leaseInfo);
        
        if(!isset($parsed['paymentdata']['vehicles']))
            return array();
        foreach($parsed['paymentdata']['vehicles'] as $key => $vehicle)
        {
            if(isset($leaseInfo[$key]))
            {
                $vehicle['payments'] = $leaseInfo[$key];
                array_shift($vehicle['payments']);
            }
            $vehicles[] = $vehicle;
        }
        return $vehicles;
    }
}