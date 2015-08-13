<?php
namespace JiraRestlib\Resources\IssueType;
use JiraRestlib\Resources\ResourcesAbstract;
use JiraRestlib\Resources\ResourcesException;

class IssueType extends ResourcesAbstract
{
    /**
     * Returns a list of all issue types visible to the user.
     * 
     * @return void      
     */
    public function getAllIssueTypes()
    {               
        $this->uri = "/rest/api/".$this->getApiVersion()."/issuetype/";
        $this->method = "GET";
    }  
    
    /**
     * Creates an issue type from a JSON representation and adds the issue newly created issue type to the default issue type scheme.
     * 
     * @param array $issueType New issue type representation
     * @return void
     */
    public function addIssueType(array $issueType)
    {        
        $this->setOptions(array("json" => $issueType));
        $this->uri = "/rest/api/".$this->getApiVersion()."/issuetype/";          
        $this->method = "POST";
    }
    
    /**
     * Returns a full representation of the issue type that has the given id.
     * 
     * @param integer $issueTypeId The id of the issue type.
     * @return void      
     */
    public function getIssueTypeById($issueTypeId)
    {               
        $this->uri = "/rest/api/".$this->getApiVersion()."/issuetype/".$issueTypeId;
        $this->method = "GET";
    }
    
    /**
     * Updates the specified issue type from a array representation.
     * 
     * @param integer $issueTypeId The id of the issue type
     * @param array $issueType New or edited fields
     * @return void      
     */
    public function updateIssueTypeById($issueTypeId, array $issueType)
    {               
        $this->setOptions(array("json" => $issueType));
        $this->uri = "/rest/api/".$this->getApiVersion()."/issuetype/".$issueTypeId;
        $this->method = "PUT";
    }
    
    /**
     * Deletes the specified issue type.
     * If the issue type has any associated issues, these issues will be migrated to the alternative issue type specified in the parameter. 
     * 
     * @param integer $issueTypeId The id of the issue type
     * @param integer $alternativeIssueTypeId The id of the alternative issue type
     * @return void      
     */
    public function deleteIssueTypeById($issueTypeId, $alternativeIssueTypeId = null)
    {                      
        $parameters["alternativeIssueTypeId"] = !empty($alternativeIssueTypeId) ? array($alternativeIssueTypeId) : array();
        $expandQuery = $this->getQueryUri($parameters);
        
        $this->uri = "/rest/api/".$this->getApiVersion()."/issuetype/".$issueTypeId.$expandQuery;      
        $this->method = "DELETE";
    }
    
    /**
     * Returns a list of all alternative issue types for the given issue type id.
     * 
     * @param integer $issueTypeId The id of the issue type.
     * @return void      
     */
    public function getIssueTypeAlternatives($issueTypeId)
    {               
        $this->uri = "/rest/api/".$this->getApiVersion()."/issuetype/".$issueTypeId."/alternatives";
        $this->method = "GET";
    }
}