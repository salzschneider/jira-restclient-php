<?php
use Mockery as m;
use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Resources\ServerInfo\ServerInfo;
use JiraRestlib\Tests\IntegrationBaseTest;

/**
 * Every variation of getRequestResult function in JiraRestlib\Api\Api Class
 */
class GeneralApiTest extends IntegrationBaseTest
{

    public function tearDown()
    {
        m::close();
    }   
    
    public function testGetServerInfoTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addCommonConfig(Config::RESPONSE_FORMAT, \JiraRestlib\Result\ResultAbstract::RESPONSE_FORMAT_OBJECT);
        $config->addRequestConfigArray($defaultOption);

        $api = new Api($config);
        $serverInfResource = new ServerInfo();
        $serverInfResource->getServerInfo(false);        

        $result = $api->getRequestResult($serverInfResource);
     
        $this->assertFalse($result->hasError()); 
    }
    
    public function testGetServerInfo2True()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addCommonConfig(Config::RESPONSE_FORMAT, \JiraRestlib\Result\ResultAbstract::RESPONSE_FORMAT_ARRAY);
        $config->addRequestConfigArray($defaultOption);

        $api = new Api($config);
        $serverInfResource = new ServerInfo();
        $serverInfResource->getServerInfo(false);  

        $result = $api->getRequestResult($serverInfResource);
        
        $this->assertFalse($result->hasError()); 
    }
    
    public function testGetServerInfo3True()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);

        $api = new Api($config);
        $serverInfResource = new ServerInfo();
        $serverInfResource->getServerInfo(false);  

        $result = $api->getRequestResult($serverInfResource);
        
        $this->assertFalse($result->hasError()); 
    }
    
    public function testGetServerInfoSetConfigTrue()
    {        
        $config = new Config(self::$jiraRestHost);
        
        //using set shortcuts
        $config->setJiraHost(self::$jiraRestHost);
        $config->setHttpClientType("guzzle");        
        $config->setSSLVerification(false);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        $config->setResponseFormat(\JiraRestlib\Result\ResultAbstract::RESPONSE_FORMAT_ARRAY);
        
        $api = new Api($config);
        $serverInfResource = new ServerInfo();
        $serverInfResource->getServerInfo(false);  

        $result = $api->getRequestResult($serverInfResource);
        
        $this->assertFalse($result->hasError()); 
        $this->assertTrue(is_array($result->getResponse()));
    }
    
    public function testGetServerInfoSetConfig2True()
    {        
        $config = new Config(self::$jiraRestHost);
        
        //using set shortcuts  
        $config->setSSLVerification(true,  __DIR__."/../../lib/apocalypse_certificate_authority.crt");
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        $config->setResponseFormat(\JiraRestlib\Result\ResultAbstract::RESPONSE_FORMAT_OBJECT);
        
        $api = new Api($config);
        $serverInfResource = new ServerInfo();
        $serverInfResource->getServerInfo(false);  

        $result = $api->getRequestResult($serverInfResource);      
        $this->assertFalse($result->hasError()); 
        $this->assertInstanceOf("stdClass", $result->getResponse());
    }
    
    /**
     * @expectedException \JiraRestlib\Config\ConfigException
     */
    public function testGetServerInfoFalse()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addCommonConfig(Config::RESPONSE_FORMAT, "WRONG");
        $config->addRequestConfigArray($defaultOption);

        $api = new Api($config);
        $serverInfResource = new ServerInfo();
        $serverInfResource->getServerInfo(false);  

        $result = $api->getRequestResult($serverInfResource);
        
        $this->assertFalse($result->hasError()); 
    }
}