<?php
use Mockery as m;
use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Resources\Configuration\Configuration;
use JiraRestlib\Tests\IntegrationBaseTest;

class ConfigurationTest extends IntegrationBaseTest
{
    public function tearDown()
    {
        m::close();
    }   
   
    public function testGetConfigurationTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $configurationResource = new Configuration();

        $configurationResource->getConfiguration();
        $result = $api->getRequestResult($configurationResource); 
        $this->assertFalse($result->hasError());     
    }    
}