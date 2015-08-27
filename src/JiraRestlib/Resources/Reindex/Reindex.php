<?php
namespace JiraRestlib\Resources\Reindex;
use JiraRestlib\Resources\ResourcesAbstract;

class Reindex extends ResourcesAbstract
{
    /**
     * Case insensitive String indicating type of reindex.
     * 
     * @var string
     */
    const BACKGROUND_PREFFERED = "BACKGROUND_PREFFERED";
    
    /**
     * Returns information on the system reindexes.
     * 
     * @param integer $taskId Optional - the id of an indexing task you wish to obtain details on
     * @return void
     */
    public function getReindexSystem($taskId = null)
    {        
        $parameters = array();
        $parameters["taskId"] = array($taskId);
                
        $expandQuery = $this->getQueryUri($parameters);        
        $this->uri = "/rest/api/".$this->getApiVersion()."/reindex/".$expandQuery;
    
        $this->method = "GET";
    }
    
    /**
     * Kicks off a reindex. Need Admin permissions to perform this reindex.
     * 
     * @param string $type Case insensitive String indicating type of reindex: FOREGROUND, BACKGROUND, BACKGROUND_PREFFERED
     * @param boolean $indexComments Indicates that comments should also be reindexed
     * @param boolean $indexChangeHistory Indicates that changeHistory should also be reindexed
     * @param boolean $indexWorklogs Indicates that changeHistory should also be reindexed
     * 
     * @return void
     */
    public function doReindexSystem($type = self::BACKGROUND_PREFFERED, $indexComments = false, $indexChangeHistory = false, $indexWorklogs = false)
    {        
        $parameters = array();
        $parameters["type"]               = array($type);        
        $parameters["indexComments"]      = $indexComments ? array("true") : array("false");
        $parameters["indexChangeHistory"] = $indexChangeHistory ? array("true") : array("false");
        $parameters["indexWorklogs"]      = $indexWorklogs ? array("true") : array("false");        
        
        $expandQuery = $this->getQueryUri($parameters);        
        $this->uri = "/rest/api/".$this->getApiVersion()."/reindex/".$expandQuery;            
        $this->method = "POST";
    }
    
     /**
     * Returns information on the system reindexes.
     * 
     * @param integer $taskId Optional - the id of an indexing task you wish to obtain details on
     * @return void
     */
    public function getReindexProgress($taskId = null)
    {        
        $parameters = array();
        $parameters["taskId"] = array($taskId);
                
        $expandQuery = $this->getQueryUri($parameters);        
        $this->uri = "/rest/api/".$this->getApiVersion()."/reindex/progress".$expandQuery;
    
        $this->method = "GET";
    }
    
    /**
     * Reindexes one or more individual issues.
     * 
     * @param string $issueId The IDs or keys of one or more issues to reindex
     * @param boolean $indexComments Indicates that comments should also be reindexed
     * @param boolean $indexChangeHistory Indicates that changeHistory should also be reindexed
     * @param boolean $indexWorklogs Indicates that changeHistory should also be reindexed
     * 
     * @return void
     */
    public function doReindexIssue($issueId, $indexComments = false, $indexChangeHistory = false, $indexWorklogs = false)
    {        
        $parameters = array();
        $parameters["issueId"]            = array($issueId);        
        $parameters["indexComments"]      = $indexComments ? array("true") : array("false");
        $parameters["indexChangeHistory"] = $indexChangeHistory ? array("true") : array("false");
        $parameters["indexWorklogs"]      = $indexWorklogs ? array("true") : array("false");        
        
        $expandQuery = $this->getQueryUri($parameters);        
        $this->uri = "/rest/api/".$this->getApiVersion()."/reindex/issue".$expandQuery;       
        $this->method = "POST";
    }
    
    /**
     * Executes any pending reindex requests. 
     * 
     * @return void
     */
    public function doReindexRequest()
    {            
        $this->uri = "/rest/api/".$this->getApiVersion()."/reindex/request";         
        $this->method = "POST";
    }
    
     /**
     * Retrieves the progress of a single reindex request.
     * @todo it doesn't work, maybe a JIRA bug? 
     * 
     * @param integer $requestId The reindex request ID
     * @return void
     */
    /*public function getReindexRequest($requestId)
    {             
        $this->uri = "/rest/api/".$this->getApiVersion()."/reindex/request/".$requestId;     
        $this->method = "GET";
    }*/
    
    /**
     * Retrieves the progress of a multiple reindex requests.
     * 
     * @param array $requestIds List of the reindex request IDs
     * @return void
     */
    public function getReindexRequestBulk(array $requestIds)
    {        
        $parameters = array();
        $parameters["requestId"] = $requestIds;
                
        $expandQuery = $this->getQueryUri($parameters);        
        $this->uri = "/rest/api/".$this->getApiVersion()."/reindex/request/bulk".$expandQuery;      
        $this->method = "GET";
    }
}