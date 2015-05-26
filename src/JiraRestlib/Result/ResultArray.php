<?php

namespace JiraRestlib\Result;
use JiraRestlib\Result\ResultAbstract;
use JiraRestlib\HttpClients\HttpClientAbstract;


class ResultArray extends ResultAbstract
{   
    /**
     * Constructor
     * 
     * @param \JiraRestlib\Result\HttpClientInterface $httpClient
     * @return void
     */
    public function __construct(HttpClientAbstract $httpClient)
    {
        parent::__construct($httpClient);
        
        $this->response = $httpClient->getRawResponse()->json();
        $this->format   = parent::RESPONSE_FORMAT_ARRAY;
    }        
    
    /**
     * Get errors
     * 
     * @return array
     */
    public function getErrors()
    {
        $returnErrors = array();
        
        if(!empty($this->response["errors"]))
        {
            $returnErrors = $this->response["errors"];
        }
        
        return $returnErrors;
    }
    
    /**
     * Get the list of error messages
     * 
     * @return array
     */
    public function getErrorMessages()
    {
        $returnMessages = array();
        
        if(!empty($this->response["errorMessages"]))
        {
            $returnMessages = $this->response["errorMessages"];
        }
        
        return $returnMessages;
    }
}