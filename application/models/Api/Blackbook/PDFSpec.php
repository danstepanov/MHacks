<?php 

class Application_Model_Api_Blackbook_PDFSpec extends Application_Model_Api_Abstract_API
{
   public function __construct()
   {
       $this->url = 'http://hearstcars.api.mashery.com/v1/UsedCarWS/UsedCarWS/PDFSpecs';
   }
    
   public function lookupByUvc($uvc)
   {
       $Parser = $this->execute('/' . $uvc);
       $FileBlob = $Parser['spec_pdf']['file_contents'];
       
       file_put_contents("pdf/$uvc.pdf", base64_decode($FileBlob));
        
       return "/pdf/$uvc.pdf";
   }
}