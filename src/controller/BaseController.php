<?php
class BaseController
{
    /** 
    * __call magic method. 
    */
    public function __call($name, $arguments)
    {
        $this->sendOutput('', array('HTTP/1.1 404 Not Found'));
    }
    /** 
    * Get URI elements. 
    * 
    * @return array 
    */
    protected function getUriSegments()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode( '/', $uri );
        return $uri;
    }
    /** 
    * Get querystring params. 
    * 
    * @return array 
    */
    protected function getQueryStringParams()
    {
        parse_str($_SERVER['QUERY_STRING'], $query);
        return $query;
    }
    /** 
    * Send API output. 
    * 
    * @param mixed $data 
    * @param string $httpHeader 
    */
    function sendOutput($data, $httpHeaders=array())
    {
        header_remove('Set-Cookie');
        if (is_array($httpHeaders) && count($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }
        echo $data;
        exit;
    }
    /**
    * Validate Request is valid
    * 
    * @param string $requestedType 
    * @param string $providedType 
    */
    protected function validateRequest($requestedType, $providedType)
    {
        if(mb_strtolower($requestedType) !== mb_strtolower($providedType)){
            return array(
                json_encode(array('error' => 'Method not supported')), 
                array('Content-Type: application/json', "HTTP/1.1 422 Unprocessable Entity"));
        }
        return true;
    }
}