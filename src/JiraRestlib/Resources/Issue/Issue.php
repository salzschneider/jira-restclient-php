<?php
namespace JiraRestlib\Resources\Issue;
use JiraRestlib\Resources\ResourcesAbstract;
use JiraRestlib\Resources\ResourcesException;

class Issue extends ResourcesAbstract
{
    /**
     * Returns a full representation of the issue for the given issue key.
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * @param array $fields The list of fields to return for the issue. By default, all fields are return
     * @param array $expand The list of fields to expand the result for more data
     * 
     * @return void
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
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * @param array $updateValues Fields, historyMetadata etc. that we wanna update.  
     *                            Field should appear either in "fields" or "update", not in both.
     * @return void
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
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * @param boolean $isDeleteSubtasks Indicating that any subtasks should also be deleted
     * 
     * @return void
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
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * @param string $name Valid jira user name
     * 
     * @return void
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
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * 
     * @return void
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
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * 
     * @return void
     */
    public function removeAssigneeIssue($idOrKey)
    {      
        $assignee = array("name" => null);        
        $this->setOptions(array("json" => $assignee));
        
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/assignee";          
        $this->method = "PUT";
    }   
    
    /**
     * Returns the meta data for editing an issue.
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * 
     * @return void
     */
    public function editMetadIssue($idOrKey)
    {      
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/editmeta/";        
        $this->method = "GET";
    }
    
    /**
     * Get a list of the transitions possible for this issue by the current user, along with fields that are required and their types.
     * The fields in the metadata correspond to the fields in the transition screen for that transition. 
     * Fields not in the screen will not be in the metadata.
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * @param boolean $isFields If true, all fields are returned
     * @param integer $transitionId id of a specific available transition 
     * 
     * @return void
     */
    public function getAllTransitionIssue($idOrKey, $isFields = false, $transitionId = null)
    {      
        $parameters = array();
        
        if($isFields)
        {
            $parameters["expand"] = array("transitions.fields");
        }
        
        if($transitionId)
        {
            $parameters["transitionId"] = array($transitionId);
        }
        
        $expandQuery = $this->getQueryUri($parameters);
        
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/transitions".$expandQuery;        
        $this->method = "GET";
    }
    
    /**
     * Perform a transition on an issue. When performing the transition you can udate or set other issue fields.
     * If a field is not configured to appear on the transition screen, then it will not be in the transition metadata, 
     * and a field validation error will occur if it is submitted.
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * @param array $data Transition info: id, filed names, hitstory metadata etc. that we wanna update or set
     * @param boolean $isFields If true, all fields are returned
     * 
     * @return void
     */
    public function performTransitionIssue($idOrKey, array $data, $isFields = false)
    {      
        $parameters = array();
        
        if(empty($data))
        {
            throw new ResourcesException("You have to set the data array. Please check the Jira API documentation.");
        }
        
        if($isFields)
        {
            $parameters["expand"] = array("transitions.fields");
        }
        
        $expandQuery = $this->getQueryUri($parameters);
        
        $this->setOptions(array("json" => $data));
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/transitions".$expandQuery;            
        $this->method = "POST";
    }
    
    /**
     * A REST sub-resource representing the voters on the issue.
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * 
     * @return void
     */
    public function getVoteIssue($idOrKey)
    {              
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/votes";            
        $this->method = "GET";
    }
    
    /**
     * Cast your vote in favour of an issue.
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * 
     * @return void
     */
    public function voteIssue($idOrKey)
    {              
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/votes";            
        $this->method = "POST";
    }
    
    /**
     * Remove your vote from an issue. (i.e. "unvote")
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * 
     * @return void
     */
    public function deleteVoteIssue($idOrKey)
    {              
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/votes";            
        $this->method = "DELETE";
    }

    /**
     * Returns the list of watchers for the issue with the given key.
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * 
     * @return void
     */
    public function getWatchersIssue($idOrKey)
    {              
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/watchers";            
        $this->method = "GET";
    }
    
    /**
     * Adds a user to an issue's watcher list.
     * 
     * @param string $idOrKey the issue id or key(i.e. JRA-1330)
     * 
     * @return void
     */
    public function addWatcherIssue($idOrKey)
    {              
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/watchers";            
        $this->method = "POST";
    }
    
    /**
     * Removes a user from an issue's watcher list.
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * @param string $username containing the name of the user to remove from the watcher list. Must not be null.
     * 
     * @return void
     */
    public function removeWatcherIssue($idOrKey, $username)
    {              
        if(empty($username))
        {
            throw new ResourcesException("You must set the username");
        }
        
        $parameters = array();
        $parameters["username"] = array($username);
        $expandQuery = $this->getQueryUri($parameters);
        
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/watchers".$expandQuery;            
        $this->method = "DELETE";
    }
    
    /**
     * Set the new value for the estimated field. e.g. "2d"
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * @param string $newAmmount the new value for the estimated field. e.g. "2d"
     * 
     * @return void
     */
    public function setNewEstimatedTimeIssue($idOrKey, $newAmmount)
    {
        $updateValues = array("update" => array("timetracking" => array(array("edit" => array("originalEstimate" => $newAmmount)))));

        $this->setOptions(array("json" => $updateValues));
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey;
        $this->method = "PUT";
    }
}