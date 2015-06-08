<?php
use Mockery as m;
use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Resources\Issue\Issue;
use JiraRestlib\Tests\ApiBaseTest;

class ApiIssueTest extends ApiBaseTest
{

    public function tearDown()
    {
        m::close();
    }   
    
    public function testGetIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => false);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);

        $api = new Api($config);
        $issueResource = new Issue();
        $issueResource->getIssue('JIR-2', array("updated", "status"), array("name", "schema"));

        $result = $api->getRequestResult($issueResource);
        
        $this->assertFalse($result->hasError()); 
    }
    
     public function testGetIssue2True()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => false);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);

        $api = new Api($config);
        $issueResource = new Issue();
        $issueResource->getIssue('JIR-3', array("updated", "status"), array("name", "schema"));

        $result = $api->getRequestResult($issueResource);
        
        $this->assertFalse($result->hasError()); 
    }
}