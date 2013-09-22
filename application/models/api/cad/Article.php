<?php 

class Application_Model_Api_Cad_Article
{
    private $collection;
    
    public function __construct()
    {
        $mongo = new MongoClient(); // connect
        $this->collection = $mongo->articles->collection;
    }
    
    public function getArticleCursor()
    {
        return $this->collection->find();
    }
    
    public function getArticleByMake($make)
    {
        $carMakesApi = new Application_Model_Api_Cad_CarMakes();
        
        $carMakes = $carMakesApi->getMakes();
        
        if(isset($carMakes[$make]))
            $makeID = $carMakes[$make];
        else
            return array();
        
        $cursor = $this->collection->find(['makeID' => $makeID]);
        
        return iterator_to_array($cursor);
    }
}