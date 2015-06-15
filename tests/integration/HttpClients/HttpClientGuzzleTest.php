<?php
use Mockery as m;
use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Tests\WrongServerInfo;
use JiraRestlib\Tests\IntegrationBaseTest;

class HttpClientGuzzleTest extends IntegrationBaseTest
{

    public function tearDown()
    {
        m::close();
    }   
    
    /**
     * @expectedException \JiraRestlib\Result\ResultException
     */
    public function testGetServerInfoFalse()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => false);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);

        $api = new Api($config);
        $serverInfResource = new WrongServerInfo();
        $serverInfResource->getWrongServerInfo();  

        $result = $api->getRequestResult($serverInfResource);
    }
    
    /**
     * @expectedException \JiraRestlib\HttpClients\HttpClientException
     */
    public function testGetServerInfoFalse2()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => false);

        $config = new Config("WRONG_URL");
        $config->addRequestConfigArray($defaultOption);

        $api = new Api($config);
        $serverInfResource = new WrongServerInfo();
        $serverInfResource->getWrongServerInfo();  

        $result = $api->getRequestResult($serverInfResource);
    }

}