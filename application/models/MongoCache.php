<?php 

class Application_Model_MongoCache
{
    private $collection;
    public function __construct()
    {
    	$mongo = new MongoClient(); // connect
    	$this->collection = $mongo->cache->collection;
    }
    
    public function insert($id, $data)
    {
        $data['cache_id'] = $id;
        
        $this->collection->insert($data);
    }
    
    public function getCacheItem($id)
    {
        $cursor = $this->collection->find(['cache_id' => $id]);
        
        $array = iterator_to_array($cursor);
        
        if(count($array) == 0)
            return null;
        return $array;
    }
}