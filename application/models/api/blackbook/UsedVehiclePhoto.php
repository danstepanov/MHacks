<?php 

class Application_Model_Api_Blackbook_UsedVehiclePhoto extends Application_Model_Api_Abstract_API
{
   public function __construct()
   {
       $this->url = 'http://autoAPI.hearst.com/v1/UsedCarWS/UsedCarWS/Photos';
   }
    
   public function lookupByUvc($uvc)
   {
       $path = "images/display/$uvc.jpg";
       if(!file_exists($path))
       {
           $parsed = $this->execute('/'.$uvc);
           $photo = $parsed['photo']['file_contents'];
         
           if($photo != '')
               file_put_contents($path, base64_decode($photo));
       }
       return '/' . $path;
   }
}