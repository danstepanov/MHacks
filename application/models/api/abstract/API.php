<?php 

abstract class Application_Model_Api_Abstract_API
{
    protected $url;
    protected $key = '56dwh8u56j4yfz4dua3app74';
    
    
    protected function execute($endpoint)
    {
        $requestUrl = $this->url . "/" . rawurlencode($endpoint) . "?api_key=" . rawurlencode($this->key);
        $APICall = curl_init();
        curl_setopt ($APICall, CURLOPT_URL, $requestUrl);
        curl_setopt ($APICall, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($APICall, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt ($APICall, CURLOPT_FAILONERROR, 1);
        $APIResponse = curl_exec($APICall);
        $Error = curl_error($APICall);
        curl_close ($APICall);
        
        if($Error != null && $Error != '')
            throw new Exception(var_export($Error, true));
        
        $parsed = json_decode($APIResponse, true);
        
        return $parsed;
    }
}