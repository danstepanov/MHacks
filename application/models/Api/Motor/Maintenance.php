<?php

class Application_Model_Api_Motor_Maintenance extends Application_Model_Api_Abstract_API
{

    private $config_id;
    private $data; 
    
    public function __construct ($config_id)
    {
        $this->escape = false;
        $this->url = 'http://autoAPI.hearst.com/Information/Content/Of/MaintenanceSchedules/WithVehicleSystem';
        $this->config_id = $config_id;
        if($this->config_id)
            $this->data = $this->lookup();
        else
            $this->data = null;
    }

    /**
     * Expose this through getData getters
     */
    private function lookup()
    {
        $mongoCache = new Application_Model_MongoCache();
        $parsed = $mongoCache->getCacheItem('maintain-' . $this->config_id);
        if ($parsed == null) {
            $parsed = $this->execute('ID/' . $this->config_id);
            
            $parsed = $this->extract($parsed);
                            
            $mongoCache->insert('maintain-' . $this->config_id, $parsed);
        } else {
            $parsed = array_shift($parsed);
        }
        return $parsed;
    }
    
    private function extract($data)
    {
        $ret = array();
        $ret['distance'] = array();
        $ret['indicator'] = array();
        $ret['other'] = array();
        
        if(is_array($data['Body']['MaintenanceSchedules']))
        {
            foreach($data['Body']['MaintenanceSchedules'] as $item)
            {
                $newItem = array();
                $newItem['description'] = $item['EWTInfo']['LiteralName'];
                $newItem['effort'] = $item['EWTInfo']['BaseLaborTime'] .
                    ' ' . $item['EWTInfo']['LaborTimeInterval'];
                $newItem['freq-month'] = $item['IntervalMonth'];
                $newItem['freq-mile'] = $item['IntervalMile'];
                if($item['FrequencyDescription'] == 'Every')
                {
                    $time = 'Every ';
                    if($item['IntervalMonth'] > 0)
                        $time .= $item['IntervalMonth'] . " months";
                    else if($item['IntervalMile'] > 0)
                        $time .= $item['IntervalMile'] . " miles";
                    $newItem['frequency'] = $time;
                    $ret['distance'][] = $newItem;
                }
                else if($item['FrequencyDescription'] == 'Indicator Light Or Interval')
                {
                    $newItem['frequency'] = "On Indicator Light";
                    $ret['indicator'][] = $newItem;
                }
                else 
                {
                    $ret['other'][] = $newItem;
                }
            }
        }
        return $ret;
    }
    
    public function getData()
    {
        
        return $this->data;
    }
}