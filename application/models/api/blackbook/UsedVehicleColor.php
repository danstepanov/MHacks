<?php 

class Application_Model_Api_Blackbook_UsedVehicleColor extends Application_Model_Api_Abstract_API
{
    private $data;
    public function __construct($uvc)
    {
       $this->url = 'http://hearstcars.api.mashery.com/v1/UsedCarWS/UsedCarWS/Colors';
       $this->data = $this->lookupByUvc($uvc);
    }
    
    public function lookupByUvc($uvc)
    {
       $parsed = $this->execute('/'.$uvc);
       return $parsed['vehicle_colors'];
    }
    
    public function getExterior()
    {
        if(isset($this->data['category_list'][0]))
            return $this->data['category_list'][0]['colors'];
        return null;
    }
    
    public function getInterior()
    {
        if(isset($this->data['category_list'][1]))
            return $this->data['category_list'][1]['colors'];
        return null;
    }
}