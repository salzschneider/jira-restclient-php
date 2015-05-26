<?php
namespace JiraRestlib\HttpClients;

/**
 * Abstract parent of all HttpClient
 * @package JiraRestlib
 */
abstract class HttpClientAbstract
{
    /**
     * Default options of the HttpClien object
     * 
     * @var array 
     */
    protected $defaultOptions = array();

    /**
     * The headers received from the response
     * 
     * @var array 
     */
    protected $responseHeaders = array();

    /**
     * The HTTP status code returned from the server
     * 
     * @var integer 
     */
    protected $responseHttpStatusCode = 0;

    /**
     * The HTTP status code returned from the server
     * 
     * @var StreamInterface|null
     */
    protected $responseBody;
    
    /**
     * Raw result of reqeuest
     * 
     * @var mixed
     */
    protected $rawResponse;
    
     /**
     * Sends a request to the server
     *
     * @param string $url The endpoint to send the request to
     * @param string $method The request method
     * @param array  $options The key value pairs to be sent in the body
     *
     * @return string Raw response from the server in JSON Format
     *
     * @throws \JiraRestlib\HttpClients\HttpClientException
     */
    abstract public function send($url, $method = 'GET', $options = array());
    
    /**
     * Set the all response variables to null, free memory
     * 
     * @return void
     */
    public function resetResponse()
    {
        $this->responseHttpStatusCode = null;
        $this->responseHeaders        = null;
        $this->responseBody           = null;
        $this->rawResponse            = null;
    }    

    /**
     * The headers returned in the response
     *
     * @return array
     */
    public function getResponseHeaders()
    {
        return $this->responseHeaders;
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
    
    /**
     * The body returned in the response
     *
     * @return mixed
     */
    public function getResponseBody()
    {
        return $this->responseBody;
    }

    /**
     * The body returned in the response
     *
     * @return mixed
     */
    public function getRawResponse()
    {
        return $this->rawResponse;
    }
    
     /**
     * Get default options used at creating HttpClient object
     * 
     * @return array
     */
    public function getDefaultOptions()
    {
        return $this->defaultOptions;
    }

    /**
     * Set default option. It will rewrite the previous default options in case of same key name
     * 
     * @param string $keyOrPath Path to set
     * @param mixed  $value Value to set at the key
     * 
     * @return void 
     */
    public function setDefaultOptions($keyOrPath, $value)
    {
        $this->defaultOptions[$keyOrPath] = $value;
    }
    
    /**
     * The body returned in the response in JSON format
     * 
     * @return string 
     */
    abstract public function getResponseJsonBody();
}