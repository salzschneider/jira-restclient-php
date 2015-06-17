<?php
use Mockery as m;
use JiraRestlib\Resources\Issue\Issue;
use JiraRestlib\Config\Config;
use JiraRestlib\Tests\IntegrationBaseTest;
use JiraRestlib\Result\ResultAbstract;
use JiraRestlib\Api\Api;

class ResultObjectTest extends IntegrationBaseTest
{

    public function tearDown()
    {
        m::close();
    }   

    public function testGetErrorTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);
        $config->addCommonConfig(Config::RESPONSE_FORMAT, ResultAbstract::RESPONSE_FORMAT_OBJECT);
        
        $api = new Api($config);
        $issueResource = new Issue();
        $issueResource->getIssue('WRONG', array("updated", "status"), array("name", "schema"));

        $result = $api->getRequestResult($issueResource);
        $errorMessages = $result->getErrorMessages();

        $this->assertSame($errorMessages[0], 'Issue Does Not Exist'); 
    }
    
    public function testGetErrorFalse()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => false);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);
        $config->addCommonConfig(Config::RESPONSE_FORMAT, ResultAbstract::RESPONSE_FORMAT_OBJECT);
        
        $api = new Api($config);
        $issueResource = new \JiraRestlib\Tests\WrongServerInfo();
        $issueResource->getWrongServerInfo();

        $result = $api->getRequestResult($issueResource);
        $this->assertTrue($result->hasError()); 
    }
    
    public function testCreateIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);
        $config->addCommonConfig(Config::RESPONSE_FORMAT, ResultAbstract::RESPONSE_FORMAT_OBJECT);
        

        $api = new Api($config);
        $issueResource = new Issue();
        $issue = array("fields" => array("project"     => array("id" => "11100"),
                                  "summary"      => "Ide a summary",
                                  "issuetype"    => array("id" => "3"),
                                  "priority"     => array("id" => "2"),
                                  "description"  => "some description here",
                                  "WRONG_ATTRIB" => array("id" => "2"),));

        $issueResource->createIssue($issue);
        $result = $api->getRequestResult($issueResource);
        
        $errors = $result->getErrors();
        $this->assertContains("WRONG_ATTRIB", $errors['WRONG_ATTRIB']);                 
    }

}