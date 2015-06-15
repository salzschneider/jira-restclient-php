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
    }    
}
