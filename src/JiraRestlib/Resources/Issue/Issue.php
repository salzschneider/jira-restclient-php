<?php
namespace JiraRestlib\Resources\Issue;
use JiraRestlib\Resources\ResourcesAbstract;
use JiraRestlib\Resources\ResourcesException;

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
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e212
     */
    public function getIssue($idOrKey, $fields = array(), $expand = array())
    {        
        $parameters = array("fields" => $fields,
                            "expand" => $expand,);
    
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
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e2
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
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e459
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
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e494
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
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e212
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
    
    /**
     * Assigns an issue to a user. 
     * 
     * @param string $idOrKey the issue id or key to update (i.e. JRA-1330)
     * @param string $name Valid jira user name
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e36
     */
    public function addAssigneeIssue($idOrKey, $name)
    {
        if(empty($name) || $name == "-1")
        {
            throw new ResourcesException("Name must't be empty or -1");
        }
        
        $assignee = array("name" => $name);        
        $this->setOptions(array("json" => $assignee));
        
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/assignee";          
        $this->method = "PUT";
    }
    
    /**
     * Automatic assignee 
     * 
     * @param string $idOrKey the issue id or key to update (i.e. JRA-1330)
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e36
     */
    public function addAutoAssigneeIssue($idOrKey)
    {
        $assignee = array("name" => "-1");        
        $this->setOptions(array("json" => $assignee));
        
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/assignee";          
        $this->method = "PUT";
    }
    
    /**
     * Remove the assignee. 
     * 
     * @param string $idOrKey the issue id or key to update (i.e. JRA-1330)
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e36
     */
    public function removeAssigneeIssue($idOrKey)
    {      
        $assignee = array("name" => null);        
        $this->setOptions(array("json" => $assignee));
        
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/assignee";          
        $this->method = "PUT";
    }
    
    /**
     * Returns all comments for an issue.
     * 
     * @param string $idOrKey the issue id or key to update (i.e. JRA-1330)
     * @param boolean $rendered provides body rendered in HTML
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e91
     */
    public function getAllCommentsIssue($idOrKey, $rendered = false)
    {      
        $parameters = array();
        
        if($rendered)
        {
            $parameters["expand"] = array("renderedBody");
        }
        
        $expandQuery = $this->getQueryUri($parameters);
   
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/comment".$expandQuery;          
        $this->method = "GET";
    }
    
    /**
     * Adds a new comment to an issue.
     * 
     * @param string $idOrKey the issue id or key to update (i.e. JRA-1330)
     * @param array $comment Comment array
     * @param boolean $rendered provides body rendered in HTML
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e91
     */
    public function addCommentIssue($idOrKey, array $comment, $rendered = false)
    {      
        $parameters = array();
        
        if($rendered)
        {
            $parameters["expand"] = array("renderedBody");
        }
        
        $expandQuery = $this->getQueryUri($parameters);
        
        $this->setOptions(array("json" => $comment));
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/comment".$expandQuery;          
        $this->method = "POST";
    }
    
    /**
     * Returns a specific comment for an issue.
     * 
     * @param string $idOrKey the issue id or key to update (i.e. JRA-1330)
     * @param integer $commentId Id of a comment (11500)
     * @param boolean $rendered provides body rendered in HTML
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e145
     */
    public function getCommentByIdIssue($idOrKey, $commentId, $rendered = false)
    {      
        $parameters = array();
        
        if($rendered)
        {
            $parameters["expand"] = array("renderedBody");
        }
        
        $expandQuery = $this->getQueryUri($parameters);
   
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/comment/".$commentId.$expandQuery;          
        $this->method = "GET";
    }
    
    /**
     * Update a specific comment for an issue.
     * 
     * @param string $idOrKey the issue id or key to update (i.e. JRA-1330)
     * @param integer $commentId Id of a comment (11500)
     * @param array $comment Comment array
     * @param boolean $rendered provides body rendered in HTML
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e145
     */
    public function updateCommentByIdIssue($idOrKey, $commentId, $comment, $rendered = false)
    {      
        $parameters = array();
        
        if($rendered)
        {
            $parameters["expand"] = array("renderedBody");
        }
        
        $expandQuery = $this->getQueryUri($parameters);
   
        $this->setOptions(array("json" => $comment));
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/comment/".$commentId.$expandQuery;          
        $this->method = "PUT";
    }
    
    /**
     * Delete a specific comment for an issue.
     * 
     * @param string $idOrKey the issue id or key to update (i.e. JRA-1330)
     * @param integer $commentId Id of a comment (11500)
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e145
     */
    public function deleteCommentByIdIssue($idOrKey, $commentId)
    {      
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/comment/".$commentId;        
        $this->method = "DELETE";
    }
    
    /**
     * Returns the meta data for editing an issue.
     * 
     * @param string $idOrKey the issue id or key to update (i.e. JRA-1330)
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e525
     */
    public function editMetadIssue($idOrKey)
    {      
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/editmeta/";        
        $this->method = "GET";
    }
}