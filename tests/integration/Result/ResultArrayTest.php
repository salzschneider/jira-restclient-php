<?php
use Mockery as m;
use JiraRestlib\Resources\Issue\Issue;
use JiraRestlib\Config\Config;
use JiraRestlib\Tests\IntegrationBaseTest;
use JiraRestlib\Result\ResultAbstract;
use JiraRestlib\Api\Api;

class ResultArrayTest extends IntegrationBaseTest
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
        $config->addCommonConfig(Config::RESPONSE_FORMAT, ResultAbstract::RESPONSE_FORMAT_ARRAY);
        
        $api = new Api($config);
        $issueResource = new Issue();
        $issueResource->getIssue('WRONG', array("updated", "status"), array("name", "schema"));

        $result = $api->getRequestResult($issueResource);
        $errorMessages = $result->getErrorMessages();

        $this->assertTrue($result->hasError()); 
        $this->assertSame($errorMessages[0], 'Issue Does Not Exist'); 
    }
    
    public function testGetErrorFalse()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => false);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);
        $config->addCommonConfig(Config::RESPONSE_FORMAT, ResultAbstract::RESPONSE_FORMAT_ARRAY);
        
        $api = new Api($config);
        $issueResource = new \JiraRestlib\Tests\WrongServerInfo();
        $issueResource->getWrongServerInfo();

        $result = $api->getRequestResult($issueResource);
        $this->assertTrue($result->hasError()); 
    }

}