<?php 

class Application_Model_Api_Cad_PerformanceData extends Application_Model_Api_Abstract_API
{
    private $id;
    private $year;
    public function __construct($id, $year)
    {
        //$id = 126;
        //$year = 2009;
        $this->id = $id;
        $this->year = $year;
        $this->url = "http://hearstcars.api.mashery.com/v1/api/perfdata/by-model-id/$id/$year/json";
    }
    
    public function lookup()
    {
        $mongoCache = new Application_Model_MongoCache();
        
        $data = $mongoCache->getCacheItem('cad-perf-' . $this->id . "-" . $this->year);
        
        if($data == null)
        {
            try{
                $data = $this->execute('');
            }catch(Exception $e) {
                return array();
            }
            $mongoCache->insert('cad-perf-' . $this->id . "-" . $this->year, $data);
        }
        else
        {
            $data = array_shift($data);
        }
        $data = $data['perfdata'];
        return $data;
    }
}