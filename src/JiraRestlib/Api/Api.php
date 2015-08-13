<?php

namespace JiraRestlib\Api;

use JiraRestlib\Api\ApiException;
use JiraRestlib\Config\Config;
use JiraRestlib\Resources\ResourcesAbstract;
use JiraRestlib\Result\ResultArray;
use JiraRestlib\Result\ResultObject;
use JiraRestlib\Result\ResultAbstract;

class Api
{        
    /**
     * Config object with connection, auth and other configuration values
     * 
     * @var JiraRestlib\Config\Config
     */
    protected $config;

    /**
     * Httpclient to connect to JIRA API
     * 
     * @var HttpClientInterface
     */
    protected $httpClient;

    /**
     * Constructor
     * 
     * @param JiraRestlib\Config\Config $config
     */
    public function __construct(Config $config)
    {
        $this->setConfig($config);
    }

    /**
     * Set a new config object and reset class (creating new httpclient)
     * 
     * @param JiraRestlib\Config\Config $config
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
        $this->reset();
    }

    /**
     * Get Config object
     * 
     * @return JiraRestlib\Config\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get current httpclient
     * 
     * @return HttpClientInterface
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }
    
    /**
     * Creating new httpclient by current Config object
     * 
     * @return void
     */
    protected function reset()
    {  
        if($this->config->hasCommonIndex(Config::HTTPCLIENT) && $this->config->getCommonConfigByIndex(Config::HTTPCLIENT) == 'curl')
        {
            $this->httpClient = new \JiraRestlib\HttpClients\HttpClientCurl($this->config->getRequestConfig());
        }
        else
        {
            $this->httpClient = new \JiraRestlib\HttpClients\HttpClientGuzzle($this->config->getRequestConfig());
        }
    }
        
    
    /**
     * Get the result of the request. The resource has to be set. 
     * 
     * @param JiraRestlib\Resources\ResourcesAbstract $resource 
     * @return JiraRestlib\Result\ResultAbstract
     */
    public function getRequestResult(ResourcesAbstract $resource)
    {       
        if(!$resource->isResourceSet())
        {
            throw new ApiException("Resource has not been set. Execute any function of resource object to get valid uri and request method. Resource type: ".  get_class($resource));
        }
        
        $urlBase = $this->config->getCommonConfigByIndex(Config::JIRA_HOST);
        $this->httpClient->send($urlBase . $resource->getUri(), $resource->getMethod(), $resource->getOptions());       
 
        if($this->config->hasCommonIndex(Config::RESPONSE_FORMAT))
        {
            $format = $this->config->getCommonConfigByIndex(Config::RESPONSE_FORMAT);
            
            switch ($format)
            {
                case ResultAbstract::RESPONSE_FORMAT_OBJECT:
                    $result = new ResultObject($this->httpClient);  
                    break;
                default:
                    $result = new ResultArray($this->httpClient);  
                    break;
            }            
        }
        else
        {
            $result = new ResultArray($this->httpClient);  
        }
 
        //remove response data from httpClient object - free some memory
        $this->httpClient->resetResponse();
        
        return $result;
    }
}
