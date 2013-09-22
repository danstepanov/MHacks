<?php 

class Application_Model_Api_Cad_VehicleByName extends Application_Model_Api_Abstract_API
{
    private $name;
    public function __construct($name)
    {
        $this->name = $name;
        $this->url = 'http://hearstcars.api.mashery.com/v1/api/vehicles/modelname/'.$name.'/json';
    }
    
    public function getModelID()
    {
        $mongoCache = new Application_Model_MongoCache();
        
        $id = $mongoCache->getCacheItem('cad-model-' . $this->name);
        
        if($id == null)
        {
            $results = $this->execute('');
            $id = $results['vehicles']['model']['id'];
            $mongoCache->insert('cad-model-' . $this->name, array("model" => $id));
        }
        else
        {
            $id = array_shift($id);
            $id = $id['model'];
        }
        return $id;
    }
}