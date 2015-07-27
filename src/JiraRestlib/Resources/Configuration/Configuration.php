<?php
namespace JiraRestlib\Resources\Configuration;
use JiraRestlib\Resources\ResourcesAbstract;

class Configuration extends ResourcesAbstract
{
    /**
     * Returns the information if the optional features in JIRA are enabled or disabled. 
     * If the time tracking is enabled, it also returns the detailed information about time tracking configuration.
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e3293
     */
    public function getConfiguration()
    {                
        $this->uri = "/rest/api/".$this->getApiVersion()."/configuration/";
        $this->method = "GET";
    }
}