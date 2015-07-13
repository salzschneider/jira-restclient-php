<?php
namespace JiraRestlib\Resources\Issue;
use JiraRestlib\Resources\ResourcesAbstract;
use JiraRestlib\Resources\ResourcesException;

class IssueWorklog extends ResourcesAbstract
{   
    /**
     * Returns all work logs for an issue.
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e717
     */
    public function getAllWorklogIssue($idOrKey)
    {              
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/worklog";            
        $this->method = "GET";
    }
    
    /**
     * Adds a new worklog entry to an issue.
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * @param array $worklog array representation of the worklog
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e717
     */
    public function addNewWorklogIssue($idOrKey, array $worklog)
    {                      
        $this->setOptions(array("json" => $worklog));
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/worklog";            
        $this->method = "POST";
    }
    
    /**
     * Add a new worklog entry to an issue and set the new value for the remaining estimate field. e.g. "2d" 
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * @param string $newRemaing the new value for the remaining estimate field. e.g. "2d"
     * @param array $worklog array representation of the worklog
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e717
     */
    public function addWorklogNewRemainingIssue($idOrKey, $newRemaing, array $worklog)
    {                          
        $parameters = array();
        $parameters["adjustEstimate"] = array("new");
        $parameters["newEstimate"] = array($newRemaing);
        $expandQuery = $this->getQueryUri($parameters);
        
        $this->setOptions(array("json" => $worklog));
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/worklog".$expandQuery;            
        $this->method = "POST";
    }
    
     /**
     * Add a new worklog entry to an issue and reduce the remaining estimate field. e.g. "2d" 
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * @param string $reduceAmmount the amount to reduce the remaining estimate by e.g. "2d"
     * @param array $worklog array representation of the worklog
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e717
     */
    public function addWorklogReduceRemainingIssue($idOrKey, $reduceAmmount, array $worklog)
    {                          
        $parameters = array();
        $parameters["adjustEstimate"] = array("manual");
        $parameters["reduceBy"] = array($reduceAmmount);
        $expandQuery = $this->getQueryUri($parameters);
        
        $this->setOptions(array("json" => $worklog));
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/worklog".$expandQuery;            
        $this->method = "POST";
    }
    
     /**
     * Add a new worklog entry to an issue and leaves the estimate as is
     * 
     * @param string $idOrKey the issue id or key to delete (i.e. JRA-1330)
     * @param array $worklog array representation of the worklog
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e769
     */
    public function addWorklogLeaveRemainingIssue($idOrKey, array $worklog)
    {               
        $parameters = array();
        $parameters["adjustEstimate"] = array("leave");
        $expandQuery = $this->getQueryUri($parameters);

        $this->setOptions(array("json" => $worklog));
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/worklog/".$expandQuery;            
        $this->method = "POST";
    }
    
     /**
     * Returns a specific work log for an issue.
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * @param integer $worklogId id of the worklog
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e769
     */
    public function getWorklogByIdIssue($idOrKey, $worklogId)
    {               
        if(empty($worklogId))
        {
            throw new ResourcesException("Worklog id mustn't be empty.");
        }
        
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/worklog/".$worklogId;            
        $this->method = "GET";
    }
    
    /**
     * Deletes an existing worklog entry
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * @param integer $worklogId id of the worklog
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e769
     */
    public function deleteWorklogIssue($idOrKey, $worklogId)
    {               
        if(empty($worklogId))
        {
            throw new ResourcesException("Worklog id mustn't be empty.");
        }

        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/worklog/".$worklogId;            
        $this->method = "DELETE";
    }
    
    /**
     * Deletes an existing worklog entry and leaves the estimate as is
     * 
     * @param string $idOrKey the issue id or key to delete (i.e. JRA-1330)
     * @param integer $worklogId id of the worklog
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e769
     */
    public function deleteWorklogLeaveRemainingIssue($idOrKey, $worklogId)
    {               
        if(empty($worklogId))
        {
            throw new ResourcesException("Worklog id mustn't be empty.");
        }

        $parameters = array();
        $parameters["adjustEstimate"] = array("leave");
        $expandQuery = $this->getQueryUri($parameters);

        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/worklog/".$worklogId.$expandQuery;            
        $this->method = "DELETE";
    }
    
    /**
     * Deletes an existing worklog entry and sets the estimate to a specific value
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * @param integer $worklogId id of the worklog
     * @param string $newRemaing the new value for the remaining estimate field. e.g. "2d"
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e769
     */
    public function deleteWorklogNewRemainingIssue($idOrKey, $worklogId, $newRemaing)
    {               
        if(empty($worklogId))
        {
            throw new ResourcesException("Worklog id mustn't be empty.");
        }
        
        $parameters = array();
        $parameters["adjustEstimate"] = array("new");
        $parameters["newEstimate"] = array($newRemaing);
        $expandQuery = $this->getQueryUri($parameters);
        
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/worklog/".$worklogId.$expandQuery;            
        $this->method = "DELETE";
    }
    
    /**
     * Deletes an existing worklog entry and increases the remaining estimate
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * @param integer $worklogId id of the worklog
     * @param string $increaseAmmount the amount to increase the remaining estimate by e.g. "2d"
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e769
     */
    public function deleteWorklogIncreaseRemainingIssue($idOrKey, $worklogId, $increaseAmmount)
    {               
        if(empty($worklogId))
        {
            throw new ResourcesException("Worklog id mustn't be empty.");
        }

        $parameters = array();
        $parameters["adjustEstimate"] = array("manual");
        $parameters["increaseBy"] = array($increaseAmmount);
        $expandQuery = $this->getQueryUri($parameters);
        
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/worklog/".$worklogId.$expandQuery;            
        $this->method = "DELETE";
    }   
    
    /**
     * Updates an existing worklog entry using its JSON representation.
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * @param integer $worklogId id of the worklog
     * @param array $worklog array representation of the worklog
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e769
     */
    public function updateWorklogIssue($idOrKey, $worklogId, array $worklog)
    {               
        if(empty($worklogId))
        {
            throw new ResourcesException("Worklog id mustn't be empty.");
        }
        
        $this->setOptions(array("json" => $worklog));
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/worklog/".$worklogId;            
        $this->method = "PUT";
    }
    
    /**
     * Updates an existing worklog entry using its JSON representation and leaves the estimate as is
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * @param integer $worklogId id of the worklog
     * @param array $worklog array representation of the worklog
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e769
     */
    public function updateWorklogLeaveRemainingIssue($idOrKey, $worklogId, array $worklog)
    {               
        if(empty($worklogId))
        {
            throw new ResourcesException("Worklog id mustn't be empty.");
        }

        $parameters = array();
        $parameters["adjustEstimate"] = array("leave");
        $expandQuery = $this->getQueryUri($parameters);
        
        $this->setOptions(array("json" => $worklog));
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/worklog/".$worklogId.$expandQuery;            
        $this->method = "PUT";
    }
    
    /**
     * Updates an existing worklog entry using its JSON representation and sets the estimate to a specific value
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * @param integer $worklogId id of the worklog
     * @param string $newRemaing the new value for the remaining estimate field. e.g. "2d"
     * @param array $worklog array representation of the worklog
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e769
     */
    public function updateWorklogNewRemainingIssue($idOrKey, $worklogId, $newRemaing, array $worklog)
    {               
        if(empty($worklogId))
        {
            throw new ResourcesException("Worklog id mustn't be empty.");
        }
        
        $parameters = array();
        $parameters["adjustEstimate"] = array("new");
        $parameters["newEstimate"] = array($newRemaing);
        $expandQuery = $this->getQueryUri($parameters);
        
        $this->setOptions(array("json" => $worklog));
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/worklog/".$worklogId.$expandQuery;            
        $this->method = "PUT";
    }
}