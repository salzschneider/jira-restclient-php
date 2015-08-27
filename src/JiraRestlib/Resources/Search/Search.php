<?php
namespace JiraRestlib\Resources\Search;
use JiraRestlib\Resources\ResourcesAbstract;

class Search extends ResourcesAbstract
{
    /**
     * Searches for issues using JQL.
     * If the JQL query is too large to be encoded as a query param you should instead POST to this resource.
     * 
     * @param string $jql Sorting the jql parameter is a full JQL expression, and includes an ORDER BY clause.
     * @param integer $startAt The index of the first issue to return (0-based)
     * @param integer $maxResults The maximum number of issues to return (defaults to 50). *     
     * @param boolean $validateQuery Whether to validate the JQL query
     * @param array $fields The list of fields to return for each issue. By default, all navigable fields are returned.
     * @param array $expand A list of the parameters to expand.
     * 
     * @return void
     */
    public function doSearchGet($jql, $startAt = 0, $maxResults = 50, $validateQuery = true, $fields = array(), $expand = array())
    {        
        $parameters = array();

        $parameters["jql"] = array($jql);
        $parameters["startAt"] = array($startAt);
        $parameters["maxResults"] = array($maxResults);
        $parameters["validateQuery"] = $validateQuery ? array("true") : array("false");        
        $parameters["fields"] = $fields;
        $parameters["expand"] = $expand;
                
        $expandQuery = $this->getQueryUri($parameters);
        
        $this->uri = "/rest/api/".$this->getApiVersion()."/search/" . $expandQuery;        
        $this->method = "GET";
    }
    
    /**
     * Performs a search using JQL. 
     * If the JQL query is too large to be encoded as a query param.
     * 
     * @param string $jql Sorting the jql parameter is a full JQL expression, and includes an ORDER BY clause.
     * @param integer $startAt The index of the first issue to return (0-based)
     * @param integer $maxResults The maximum number of issues to return (defaults to 50). *     
     * @param boolean $validateQuery Whether to validate the JQL query
     * @param array $fields The list of fields to return for each issue. By default, all navigable fields are returned.
     * @param array $expand A list of the parameters to expand.
     * 
     * @return void
     */
    public function doSearchPost($jql, $startAt = 0, $maxResults = 50, $validateQuery = true, $fields = array(), $expand = array())
    {        
        $parameters = array();

        $parameters["jql"] = $jql;
        $parameters["startAt"] = $startAt;
        $parameters["maxResults"] = $maxResults;
        $parameters["validateQuery"] = $validateQuery ? "true" : "false";        
        $parameters["fields"] = $fields;
        $parameters["expand"] = $expand;
        
        $this->setOptions(array("json" => $parameters));
        $this->uri = "/rest/api/".$this->getApiVersion()."/search/";        
        $this->method = "POST";
    }
}