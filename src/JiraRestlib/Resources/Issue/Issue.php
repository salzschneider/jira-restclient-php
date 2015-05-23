<?php
namespace JiraRestlib\Resources\Issue;
use JiraRestlib\Resources\ResourcesAbstract;

class Issue extends ResourcesAbstract
{
    /**
     * Returns a full representation of the issue for the given issue key.
     * 
     * @param string $idOrKey the issue id or key to update (i.e. JRA-1330)
     * @param array $fields The list of fields to return for the issue. By default, all fields are returne
     * @param array $expand The list of fields to expand the result for more data
     * 
     * @return void
     * 
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
     * @param array $options the key value pairs to be sent in the body
     * @return void
     * 
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e2786
     */
    public function createIssue($options)
    {        
        $this->setOptions($options);
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/";
        $this->method = "POST";
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
}