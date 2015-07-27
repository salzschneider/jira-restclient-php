<?php
use Mockery as m;
use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Resources\ServerInfo\ServerInfo;
use JiraRestlib\Tests\IntegrationBaseTest;

class ServerInfoTest extends IntegrationBaseTest
{
    public function tearDown()
    {
        m::close();
    }   
   
    public function testGetServerInfoTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $serverInfoResource = new ServerInfo();

        $serverInfoResource->getServerInfo();
        $result = $api->getRequestResult($serverInfoResource);  
        $this->assertFalse($result->hasError()); 
        
        $reponse = $result->getResponse();        
        $this->assertFalse(array_key_exists("healthChecks", $reponse));         
    }
    
    public function testGetServerInfoHealthCheckTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $serverInfoResource = new ServerInfo();

        $serverInfoResource->getServerInfo(true);
        $result = $api->getRequestResult($serverInfoResource);  
        $this->assertFalse($result->hasError()); 
        
        $reponse = $result->getResponse();        
        $this->assertTrue(array_key_exists("healthChecks", $reponse));         
    } 
}