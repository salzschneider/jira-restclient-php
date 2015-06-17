<?php

namespace JiraRestlib\Result;
use JiraRestlib\HttpClients\HttpClientAbstract;
use JiraRestlib\Result\ResultException;

abstract class ResultAbstract
{
    /**
     * Response in "object" or "array" representation
     * 
     * @var string 
     */
    protected $response;
    
    /**
     * Response http header
     * 
     * @var type array
     */
    protected $responseHeaders;
    
    /**
     * Response http status code e.g. 200
     * 
     * @var int
     */
    protected $responseHttpStatusCode;
    
    /**
     * Valid response result types
     */
    const RESPONSE_FORMAT_ARRAY  = "array";
    const RESPONSE_FORMAT_OBJECT = "object";
    
    /**
     * Current respone format
     * 
     * @var string 
     */
    protected $format;
    
    /**
     * Constructor
     * 
     * @param \JiraRestlib\Result\HttpClientInterface $httpClient
     * @return void
     */
    public function __construct(HttpClientAbstract $httpClient)
    {             
        $this->responseHeaders        = $httpClient->getResponseHeaders();
        $this->responseHttpStatusCode = $httpClient->getResponseHttpStatusCode();
    }

    /**
     * Get response JSON string in "array" or "object" representation
     * 
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }
    
    /**
     * Get current format type of result e.g. json, array, object
     * 
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
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
     * whether the response is an error response or not
     * (http status code is bigger than 300)
     * 
     * @return boolean     
     */
    public function hasError()
    {
        $hasError = false;
        $httpStatusCode = $this->getResponseHttpStatusCode();
        
        if(!($httpStatusCode >= 200 && $httpStatusCode < 300))
        {
            $hasError = true;
        }
        
        return $hasError;
    }
    
    /**
     * Get errors
     * 
     * @return array
     */
    abstract public function getErrors();
    
    /**
     * Get the list of error messages
     * 
     * @return array
     */
    abstract public function getErrorMessages();
}