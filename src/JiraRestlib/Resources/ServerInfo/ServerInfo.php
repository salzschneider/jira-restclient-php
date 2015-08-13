<?php
namespace JiraRestlib\Resources\ServerInfo;
use JiraRestlib\Resources\ResourcesAbstract;

class ServerInfo extends ResourcesAbstract
{
    /**
     * Returns general information about the current JIRA server.
     * 
     * @param boolean $doHealthCheck
     * 
     * @return void
     */
    public function getServerInfo($doHealthCheck = false)
    {        
        $parameters = array("doHealthCheck" => array(($doHealthCheck) ? "true" : "false"));
    
        $expandQuery = $this->getQueryUri($parameters);
        
        $this->uri = "/rest/api/".$this->getApiVersion()."/serverInfo/" . $expandQuery;
        $this->method = "GET";
    }
}