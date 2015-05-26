<?php
namespace JiraRestlib\Resources\Issue;
use JiraRestlib\Resources\ResourcesAbstract;

class Issue extends ResourcesAbstract
{
    /**
     * Returns a full representation of the issue for the given issue key.
     * 
     * @param string $idOrKey the issue id or key to update (i.e. JRA-1330)
     * @param array $fields The list of fields to return for the issue. By default, all fields are return
     * @param array $expand The list of fields to expand the result for more data
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e2996
     */
    public function getIssue($idOrKey, $fields = array(), $expand = array())
    {        
        $parameters = array("fields"     => $fields,
                            "expand"    => $expand,);
    
        $expandQuery = $this->getQueryUri($parameters);
        
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/" . $idOrKey . $expandQuery;
        $this->method = "GET";
    }
    
    /**
     * Creates an issue or a sub-task from a JSON representation.
     * 
     * @param array $issue the key value pairs to be sent in the body
     * array("fields" => array("project"     => array("id" => "11100"),
     *                         "summary"     => "Ide a summary",
     *                         "issuetype"   => array("id" => "3"),
     *                         "priority"    => array("id" => "2"),
     *                         "description" => "some description here"));
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e2786
     */
    public function createIssue($issue)
    {        
        $this->setOptions(array("json" => $issue));
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/";
        $this->method = "POST";
    }
    
    /**
     * Creates many issues in one bulk operation.
     * 
     * @param array $issueList
     * $issue1 = array("fields" => array("project"     => array("id" => "11100"),
     *                             "summary"     => "Issue 1 Bulk",
     *                            "issuetype"   => array("id" => "3"),
     *                             "priority"    => array("id" => "2"),
     *                             "description" => "some description here, bulk 2"));
     *
     * $issue2 = array("fields" => array("project"     => array("id" => "11100"),
     *                             "summary"     => "Issue 2 Bulk",
     *                             "issuetype"   => array("id" => "3"),
     *                             "priority"    => array("id" => "2"),
     *                             "description" => "some description here, bulk 2"));
     *
     * $issueList = array("issueUpdates" => array($issue1, $issue2));
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e3243
     */
    public function createIssueBulk($issueList)
    {        
        $this->setOptions(array("json" => $issueList));
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/bulk";
        $this->method = "POST";
    }
    
    /**
     * Edits an issue from a JSON representation.
     * 
     * @param string $idOrKey the issue id or key to update (i.e. JRA-1330)
     * @param array $updateValues Fields, historyMetadata etc. that we wanna update.  
     *                            Field should appear either in "fields" or "update", not in both.
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e4523
     */    
    public function editIssue($idOrKey, $updateValues)
    {
        $this->setOptions(array("json" => $updateValues));
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey;
        $this->method = "PUT";
    }
    
    /**
     * Returns the meta data for creating issues. 
     * 
     * @param array $projectIds Combined with the projectKeys param, lists the projects with which to filter the results. 
     *                          If absent, all projects are returned. 
     * @param array $projectKeys Combined with the projectIds param, lists the projects with which to filter the results. 
     *                           If null, all projects are returned. 
     * @param array $issueTypeIds Combinded with issuetypeNames, lists the issue types with which to filter the results. 
     *                            If null, all issue types are returned.
     * @param array $issueTypeNames Combinded with issuetypeIds, lists the issue types with which to filter the results. 
     *                              If null, all issue types are returned. 
     * @param boolean $isFields If true, all fields are returned
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e3278
     */
    public function createMetadata(array $projectIds = array(), array $projectKeys = array(), array $issueTypeIds = array(), array $issueTypeNames = array(), $isFields = false)
    {        
        $parameters = array("projectIds"     => $projectIds,
                            "projectKeys"    => $projectKeys,
                            "issuetypeIds"   => $issueTypeIds,
                            "issuetypeNames" => $issueTypeNames,);
        
        if($isFields)
        {
            $parameters["expand"] = array("projects.issuetypes.fields");
        }
    
        $expandQuery = $this->getQueryUri($parameters);
        
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/createmeta" . $expandQuery;              
        $this->method = "GET";
    }
    
    /**
     * Delete an issue. If the issue has subtasks you must set the parameter deleteSubtasks=true to delete the issue. 
     * You cannot delete an issue without its subtasks also being deleted.
     * 
     * @param string $idOrKey the issue id or key to update (i.e. JRA-1330)
     * @param boolean $isDeleteSubtasks Indicating that any subtasks should also be deleted
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e4500
     */
    public function deleteIssue($idOrKey, $isDeleteSubtasks = false)
    {
        $parameters = array();
        
        if($isDeleteSubtasks)
        {
            $parameters["deleteSubtasks"] = array("true");
        }
        
        $expandQuery = $this->getQueryUri($parameters);
        
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey.$expandQuery;          
        $this->method = "DELETE";
    }
}