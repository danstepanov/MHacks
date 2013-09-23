<?php

class Application_Model_Api_Bing_Bing
{
	private $term; 

	public function __construct($term)
	{
		$this->term = $term;
	}

	public function getImageUrl()
	{
		$images = $this->request();

		return $images['d']['results'][0]['MediaUrl'];
	}

	private function request()
	{
		$acctKey = 'WjlZ1h0nhQCSYY2CGx275L+GhS0YHaWc4GXMOyknprU';
        $rootUri = 'https://api.datamarket.azure.com/Bing/Search';
        $query = $this->term;
        $serviceOp = 'Image';
        $market = 'en-us';
        $query = urlencode("'$query'");
        $market = urlencode("'$market'");
        $requestUri = "$rootUri/$serviceOp?\$format=json&Query=$query&Market=$market&ImageFilters='Size:Large'";
        $auth = base64_encode("$acctKey:$acctKey");
        $data = array(
        'http' => array(
            'request_fulluri' => true,
            'ignore_errors' => true,
            'header' => "Authorization: Basic $auth")
        );

        $context = stream_context_create($data);

        $response = file_get_contents($requestUri, 0, $context);

        $res = json_decode($response, true);

        return $res;
	}
}