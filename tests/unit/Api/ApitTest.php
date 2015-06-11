<?php

use Mockery as m;
use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Tests\UnitBaseTest;

class ApiTest extends UnitBaseTest
{    
    public function tearDown()
    {
        m::close();
    }   
    
    protected function getNewApi()
    {
        $config = new Config("http://jira.com", "guzzle");
        return new Api($config);
    }

    
    public function validConstructor()
    {
        return array(
          array(new Config("http://jira.com", "guzzle")),
          array(new Config("http://jira.com", "curl")),
        );
    }
    
    public function invalidConstructor()
    {
        return array(
          array(null),
          array(1),
          array("WRONG"),  
          array(new stdClass()),
        );
    }        
    
    /**
     * @dataProvider validConstructor
     */
    public function testCreateApiTrue($Config)
    {
        $api = new Api($Config);        
        $this->assertInstanceOf("JiraRestlib\Api\Api", $api);
    }
    
    /**
     * @dataProvider invalidConstructor
     * @expectedException PHPUnit_Framework_Error
     */
    public function testCreateConfigFalse($Config)
    {        
        $api = new Api($Config);    
    }   
    
    public function testGetHttpClientTrue()
    {
        $api = $this->getNewApi();
        $httpClient = $api->getHttpClient();
 
        $this->assertInstanceOf("\JiraRestlib\HttpClients\HttpClientAbstract", $httpClient);
    }
        
    public function testSetConfigTrue()
    {
        $api = $this->getNewApi(); 
        $apiOldConfig = $api->getConfig();
        $apiOldUrl = $apiOldConfig->getCommonConfigByIndex(Config::JIRA_HOST);
        
        $api->setConfig(new Config("http://different.com", "curl"));
        $apiConfig = $api->getConfig();
        $apiUrl = $apiConfig->getCommonConfigByIndex(Config::JIRA_HOST);
    
        $this->assertNotSame($apiOldUrl, $apiUrl);
    }
    
    public function testResetTrue()
    {
        $config = new Config("http://jira.com", "guzzle");
        $api = new Api($config);        
        $httpClientFirst = $api->getHttpClient();
        
        $config = new Config("http://jira.com", "curl");
        $api = new Api($config);        
        $httpClientSecond = $api->getHttpClient();
      
        $this->assertNotSame(get_class($httpClientFirst), get_class($httpClientSecond));
    }

}
