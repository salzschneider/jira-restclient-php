<?php

use Mockery as m;
use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Tests\UnitBaseTest;
use JiraRestlib\Resources\Issue\Issue;
use JiraRestlib\Resources\Attachments\Attachments;

class ApiTest extends UnitBaseTest
{    
    const JIRA_URL = "http://jira.com";
    
    public function tearDown()
    {
        m::close();
    }   
    
    protected function getNewApi()
    {
        $config = new Config(self::JIRA_URL, "guzzle");
        return new Api($config);
    }

    
    public function validConstructor()
    {
        return array(
          array(new Config(self::JIRA_URL, "guzzle")),
          array(new Config(self::JIRA_URL, "curl")),
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
    
    public function invalidResources()
    {
        return array(
          array(null),
          array(1),
          array("WRONG"),  
          array(new stdClass()),
        );
    } 
    
    public function notSetResources()
    {
        return array(
          array(new Issue()),
          array(new Attachments()),
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
    
    public function testGetConfigTrue()
    {
        $api = $this->getNewApi();
        $apiConfig = $api->getConfig();
 
        $this->assertSame(self::JIRA_URL, $apiConfig->getCommonConfigByIndex(Config::JIRA_HOST));
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
        
    /**
     * @dataProvider invalidResources
     * @expectedException PHPUnit_Framework_Error
     */
    public function testGetRequestResultFalse($resource)
    {
        $config = new Config(self::JIRA_URL, "guzzle");
        $api = new Api($config);        
                
        $api->getRequestResult($resource);
    }
    
    /**
     * @dataProvider notSetResources
     * @expectedException \JiraRestlib\Api\ApiException
     */
    public function testGetRequestResultFalse2($resource)
    {
        $config = new Config(self::JIRA_URL, "guzzle");
        $api = new Api($config);  
                
        $api->getRequestResult($resource);
    }
}
