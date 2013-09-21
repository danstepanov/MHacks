<?php 

class Application_Model_Api_Blackbook_UsedVehicle extends Application_Model_Api_Abstract_API
{
   public function __construct()
   {
       $this->url = 'http://autoAPI.hearst.com/v1/UsedCarWS/UsedCarWS/UsedVehicle';
   }
    
   public function lookupByUvc($uvc)
   {
       $parsed = $this->execute('UVC/'.$uvc);

       $parsed = $parsed['used_vehicles']['used_vehicle_list'][0];
       
       $parsed['display_title'] = $parsed['full_year'] . ' ' . $parsed['make'] 
            . ' ' . $parsed['model'] . ' ' . $parsed['series'];
       
       $usedVehiclePhotoAPI = new Application_Model_Api_Blackbook_UsedVehiclePhoto();
       $photo = $usedVehiclePhotoAPI->lookupByUvc($uvc);
       $parsed['photo'] = $photo;
       
       $usedVehicleColorAPI = new Application_Model_Api_Blackbook_UsedVehicleColor($uvc);
       $parsed['external_colors'] = $usedVehicleColorAPI->getExterior();
       $parsed['interior_colors'] = $usedVehicleColorAPI->getInterior();
       return $parsed;
   }
}