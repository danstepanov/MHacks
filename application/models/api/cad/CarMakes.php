<?php 

class Application_Model_Api_Cad_CarMakes extends Application_Model_Api_Abstract_API
{
    public function __construct()
    {
        $this->url = 'http://hearstcars.api.mashery.com/v1/api/vehicles/makes/all/json';
    }
    
    public function getMakes()
    {
        $results = $this->execute('');
        $results = $results['vehicles']['makes'];   
        
        $return = array();
        
        foreach($results as $result)
        {
            $return[$result['name']] = $result['id'];
        }
        
        return $return;
    }
}