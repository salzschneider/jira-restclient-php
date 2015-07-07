<?php
use Mockery as m;
use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Resources\ServerInfo\ServerInfo;
use JiraRestlib\Tests\IntegrationBaseTest;

class HttpClientCurlTest extends IntegrationBaseTest
{

    public function tearDown()
    {
        m::close();
    }   

    public function testGetServerInfoNotVerifiedTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addCommonConfig(Config::HTTPCLIENT, 'curl');
        $config->addRequestConfigArray($defaultOption);

        $api = new Api($config);
        $serverInfResource = new ServerInfo();
        $serverInfResource->getServerInfo(false);        

        $result = $api->getRequestResult($serverInfResource);
     
        $this->assertFalse($result->hasError()); 
    }
    
    public function testGetServerInfoVerifiedTrue()
    {
        $defaultOption = array("verify"    => true);

        $config = new Config("https://jira.atlassian.com/");
        $config->addCommonConfig(Config::HTTPCLIENT, 'curl');
        $config->addRequestConfigArray($defaultOption);

        $api = new Api($config);
        $serverInfResource = new ServerInfo();
        $serverInfResource->getServerInfo(false);        

        $result = $api->getRequestResult($serverInfResource);
        $responseHeader = $result->getResponseHeaders();

        $this->assertFalse($result->hasError()); 
    }
    
    public function testGetServerInfoCertTrue()
    {
        $defaultOption = array("verify"    => __DIR__."/../../lib/apocalypse_certificate_authority.crt");

        $config = new Config(self::$jiraRestHost);
        $config->addCommonConfig(Config::HTTPCLIENT, 'curl');
        $config->addRequestConfigArray($defaultOption);

        $api = new Api($config);
        $serverInfResource = new ServerInfo();
        $serverInfResource->getServerInfo(false);        

        $result = $api->getRequestResult($serverInfResource);
        $responseHeader = $result->getResponseHeaders();

        $this->assertFalse($result->hasError()); 
    }
    
    /**
     * @expectedException \JiraRestlib\HttpClients\HttpClientException
     */
    public function testGetServerInfoCertFalse()
    {
        $defaultOption = array("verify"    => __DIR__."/WRONG");

        $config = new Config(self::$jiraRestHost);
        $config->addCommonConfig(Config::HTTPCLIENT, 'curl');
        $config->addRequestConfigArray($defaultOption);

        $api = new Api($config);
        $serverInfResource = new ServerInfo();
        $serverInfResource->getServerInfo(false);        

        $result = $api->getRequestResult($serverInfResource);
        $responseHeader = $result->getResponseHeaders();

        $this->assertFalse($result->hasError()); 
    }
    
    /**
     * @expectedException \JiraRestlib\HttpClients\HttpClientException
     */
    public function testGetServerInfoErrorFalse()
    {
        $defaultOption = array("verify"    => false);

        $config = new Config("WRONG");
        $config->addCommonConfig(Config::HTTPCLIENT, 'curl');
        $config->addRequestConfigArray($defaultOption);

        $api = new Api($config);
        $serverInfResource = new ServerInfo();
        $serverInfResource->getServerInfo(false);        

        $result = $api->getRequestResult($serverInfResource);
        $responseHeader = $result->getResponseHeaders();

        $this->assertFalse($result->hasError()); 
    }

}