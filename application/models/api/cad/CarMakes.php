<?php 

class Application_Model_Api_Cad_CarMakes extends Application_Model_Api_Abstract_API
{
    public function __construct()
    {
        $this->url = 'http://hearstcars.api.mashery.com/v1/api/vehicles/makes/all/json';
    }
    
    public function getMakes()
    {
        $mongoCache = new Application_Model_MongoCache();
        
        $return = $mongoCache->getCacheItem('makes');
        
        if($return == null)
        {
            $results = $this->execute('');
            $results = $results['vehicles']['makes'];   
            
            $return = array();
            
            foreach($results as $result)
            {
                $return[$result['name']] = $result['id'];
            }
            $mongoCache->insert('makes', $return);
        }
        else
        {
            $return = array_shift($return);
        }
        return $return;
    }
}