<?php
namespace JiraRestlib\Tests;

use JiraRestlib\Resources\ResourcesAbstract;

class WrongServerInfo extends ResourcesAbstract
{
    /**
     * Generate Guzzle error for tests
     * 
     * @return void
     */
    public function getWrongServerInfo()
    {         
        $this->uri = "/rest/api/".$this->getApiVersion()."/WRONG/";
        $this->method = "GET";
    }
}