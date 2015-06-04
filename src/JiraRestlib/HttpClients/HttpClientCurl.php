<?php
namespace JiraRestlib\HttpClients;
use JiraRestlib\HttpClients\HttpClientException;
use JiraRestlib\HttpClients\HttpClientAbstract;
use Curl\Curl;

/**
 * Class HttpClientCurl
 * @package JiraRestlib
 */
class HttpClientCurl extends HttpClientAbstract
{
    /**
     * The client error message
     * 
     * @var string 
     */
    protected $curlErrorMessage = '';

    /**
     * The curl client error code
     * 
     * @var integer
     */
    protected $curlErrorCode = 0;

    /**
     * Procedural curl as object
     * 
     * @var Curl\Curl 
     */
    protected $curlClient;

    /**
     * Constructor
     */
    public function __construct(array $defaultOptions = array())
    {
        $this->defaultOptions = $defaultOptions;
        $this->curlClient     = new Curl();        
    }

    /**
     * Sends a request to the server
     *
     * @param string $url The endpoint to send the request to
     * @param string $method The request method
     * @param array  $options The key value pairs to be sent in the body
     *
     * @return string Raw response from the server
     *
     * @throws \JiraRestlib\HttpClients\HttpClientException
     */
    public function send($url, $method = 'GET', $options = array())
    {
        $this->setConnection($method, array_merge($this->defaultOptions, $options));
        $this->sendRequest($url, $method);
        
        if ($this->curlErrorCode)
        {
            throw new HttpClientException($this->curlErrorMessage, $this->curlErrorCode);
        }
        
        $this->curlClient->close();
        
        return $this->rawResponse;
    }

     /**
     * Set the cURL parameters
     *
     * @param string $method The request method
     * @param array  $options The key value pairs to be sent in the body
     *
     * @return void
     */
    protected function setConnection($method = 'GET', array $options = array())
    {        
        $this->curlClient->setOpt(CURLOPT_CONNECTTIMEOUT, 10);
        $this->curlClient->setOpt(CURLOPT_TIMEOUT, 60);
        $this->curlClient->setOpt(CURLOPT_RETURNTRANSFER, true);
        $this->curlClient->setOpt(CURLOPT_HEADER, false);
        
        $this->setAuth($options);
        $this->setVerify($options);
    }
    
    /**
     * Set basic auth if it exists in config
     * 
     * @param array  $options The key value pairs to be sent in the body
     * @return void
     */
    protected function setAuth(array $options)
    {
        if(!empty($options["auth"]) && is_array($options["auth"]))
        {
            $this->curlClient->setBasicAuthentication($options["auth"][0], $options["auth"][1]);
        }
    }
    
     /**
     * Set SSL verification if it exists in config
     * 
     * @param array  $options The key value pairs to be sent in the body
     * @return void
     */
    protected function setVerify(array $options)
    {        
        if ($options["verify"] === false)
        {
            $this->curlClient->setOpt(CURLOPT_SSL_VERIFYHOST, 0);
            $this->curlClient->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        }
        else
        {
            $this->curlClient->setOpt(CURLOPT_SSL_VERIFYHOST, 2);
            $this->curlClient->setOpt(CURLOPT_SSL_VERIFYPEER, true);

            if (is_string($options["verify"]))
            {
                if (!file_exists($options["verify"]))
                {
                    throw new HttpClientException( "SSL CA bundle not found: ".$options["verify"]);
                }
                
                $this->curlClient->setOpt(CURLOPT_CAINFO, $options["verify"]);                
            }
        }
    }
    
     /**
     * Send the request and get the raw response from curl
     * 
     * @param string $url The endpoint to send the request to
     * @param string $method The request method
     * @return void
     */
    protected function sendRequest($url, $method = 'GET')
    {                
        if ($method === 'GET')
        {            
            $this->curlClient->get($url);
        }
        
        if ($method === 'DELETE' || $method === 'PUT')
        {
            
        }
        
        if ($this->curlClient->error) 
        {
            $this->curlErrorMessage  = $this->curlClient->curl_error_message;
            $this->curlErrorCode     = $this->curlClient->curl_error_code;
        }
        else 
        {
           $this->curlClient->response;
        }
        
        //@todo has to be an array - guzzle
        $this->responseHeaders        = $this->curlClient->response_headers; 
        $this->responseHttpStatusCode = $this->curlClient->http_status_code;        
        $this->rawResponse            = $this->curlClient->raw_response;
    }
    
    /**
     * The body returned in the response in JSON format
     * 
     * @return string 
     */
    public function getResponseJsonBody()
    {
        return $this->rawResponse;
    }
    
    /**
     * The body returned in the response
     *
     * @return mixed
     */
    public function getResponseBody()
    {
        return $this->rawResponse;
    }
    
    /**
     * The headers returned in the response
     *
     * @return array
     */
    public function getResponseHeaders()
    {
        $returnArray = array();
        
        foreach($this->responseHeaders as $key => $values)
        {
            if(is_array($values))
            {
                foreach($values as $value)
                {
                    $returnArray[$key][] = $value;
                }
            }
            else
            {
                $returnArray[$key][] = $values;
            }
        }
        
        return $returnArray;
    }
        
    /**
     * The HTTP status response code
     *
     * @return int
     */
    public function getResponseHttpStatusCode()
    {
        return $this->responseHttpStatusCode;
    }
}
