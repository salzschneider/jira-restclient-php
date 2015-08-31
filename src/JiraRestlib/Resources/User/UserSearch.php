<?php
namespace JiraRestlib\Resources\User;
use JiraRestlib\Resources\ResourcesAbstract;

class UserSearch extends ResourcesAbstract
{
    /**
     * Returns a list of users that match the search string. This resource cannot be accessed anonymously.
     * 
     * @param string $needle A query string (substring) used to search username, name or e-mail address
     * @param integer $startAt The index of the first user to return (0-based)
     * @param integer $maxResults The maximum number of users to return (defaults to 50). The maximum allowed value is 1000
     * @param boolean $includeActive If true, then active users are included in the results (default true)
     * @param boolean $includeInactive If true, then inactive users are included in the results (default false)
     * @return void
     */
    public function doSearchUser($needle, $startAt = 0, $maxResults = 50, $includeActive = true, $includeInactive = false)
    {                
        $parameters = array();
        $parameters["username"]        = array($needle);       
        $parameters["startAt"]         = array($startAt);
        $parameters["maxResults"]      = array($maxResults);
        $parameters["includeActive"]   = $includeActive ? array("true") : array("false");
        $parameters["includeInactive"] = $includeInactive ? array("true") : array("false"); 
          
        $expandQuery = $this->getQueryUri($parameters);      
        $this->uri = "/rest/api/".$this->getApiVersion()."/user/search".$expandQuery;
        $this->method = "GET";
    }      
}