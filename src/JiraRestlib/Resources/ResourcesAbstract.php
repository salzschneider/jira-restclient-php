<?php
namespace JiraRestlib\Resources;

/**
 * Parent class of all resource class
 * @package JiraRestlib
 */
abstract class ResourcesAbstract
{
    /**
     * Request type
     * 
     * @var string 
     */
    protected $method = "GET";
    
    /**
     * Request uri
     * 
     * @var string 
     */
    protected $uri = "";
    
    /**
     * JIRA API official version. It can be the "latest" too.
     * 
     * @var string 
     */
    protected $apiVersion = "2";
    
    /**
     * The key value pairs to be sent in the body
     * 
     * @var array 
     */
    protected $options = array();
    
    /**
     * Constructor
     * 
     * @param string $apiVersion Defined JIRA API version
     * @return void
     */
    public function __construct($apiVersion = "2")
    {
        $this->apiVersion = $apiVersion;
    }
    
    /**
     * Get defined JIRA API version e.g. "2" or "latest"
     * 
     * @return string
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }
    
    /**
     * Get request uri e.g. /rest/api/2/issue/picker
     * 
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }
    
    /**
     * Get request type: POST, GET, PUT, DELETE
     * 
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
    
    /**
     * Get options - the key value pairs to be sent in the body
     * 
     * @return string
     */
    public function getOptions()
    {
        return $this->options;
    }
    
    /**
     * Set options
     * 
     * @param array $options the key value pairs to be sent in the body
     * @return void
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }
    
    /**
     * Resource has to be set before using it in Api request.
     * 
     * @return boolean
     */
    public function isResourceSet()
    {
        return !empty($this->uri);
    }  
    
    /**
     * Generate an universal query string
     * 
     * @param array $parameters
     * Array
     * (
     *    [projectIds] => Array
     *    (
     *         [0] => 1110
     *         [1] => 1111
     *    )
     *    [projectKeys] => Array
     *    (
     *         [0] => BAL
     *    )
     * ) 
     * @return string
     */
    protected function getQueryUri(array $parameters)
    {
        $returnUri = "";
        $isFirst = true;
        
        foreach($parameters as $indexName => $values)
        {
            if(!empty($values))
            {
                if($isFirst)
                {                  
                    $returnUri .= "?";
                    $isFirst = false;
                }
                else
                {
                    $returnUri .= "&";
                }
                
                $uriParams = array_map("urlencode", $values);
                $returnUri .= $indexName."=".implode(",", $uriParams);
            }
        }
        
        return $returnUri;
    }
}