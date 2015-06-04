<?php
namespace JiraRestlib\HttpClients;

use JiraRestlib\HttpClients\HttpClientInterface;

/**
 * Abstract parent of all HttpClient
 * @package JiraRestlib
 */
abstract class HttpClientAbstract implements HttpClientInterface
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
}