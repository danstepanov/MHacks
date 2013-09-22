<?php

class Application_Model_Api_Blackbook_VehicleVin extends Application_Model_Api_Abstract_API
{

    private $vin;
    private $data;
    
    public function __construct ($vin)
    {
        $this->escape = false;
        $this->url = 'http://autoAPI.hearst.com/Information/Vehicle/Search';
        $this->vin = substr($vin, 0, 8) . "x" . substr($vin, 8, 9);
        $this->data = $this->lookup();
    }

    private function lookup ()
    {
        $mongoCache = new Application_Model_MongoCache();
        $parsed = $mongoCache->getCacheItem('motor-' . $this->vin);
        $parsed = null;
        if ($parsed == null) {
            $parsed = $this->execute('ByVIN/' . $this->vin);
            
            $mongoCache->insert('motor-' . $this->vin, $parsed);
        } else {
            $parsed = array_shift($parsed);
        }
        return $parsed;
    }
    
    public function getConfigurationData()
    {
        if(!isset($this->data['Body']['Configurations'][0]))
        	return null;
        return $this->data['Body']['Configurations'][0];
    }
    
    public function getModelID()
    {
        if(!isset($this->data['Body']['Vehicles'][0]['BaseVehicleInfo']['Model']['ModelID']))
        	return null;
        return $this->data['Body']['Vehicles'][0]['BaseVehicleInfo']['Model']['ModelID'];
    }
}