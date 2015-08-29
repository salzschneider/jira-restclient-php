<?php
use Mockery as m;
use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Resources\Resolution\Resolution;
use JiraRestlib\Tests\IntegrationBaseTest;

class ResolutionTest extends IntegrationBaseTest
{
    public function tearDown()
    {
        m::close();
    }   
   
    public function testGetResolutionTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $reindexResource = new Resolution();

        $reindexResource->getResolution();
        $result = $api->getRequestResult($reindexResource);  
        $this->assertFalse($result->hasError());      
    }  
    
     public function testGetResolutionByIdTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $reindexResource = new Resolution();

        $reindexResource->getResolution();
        $result = $api->getRequestResult($reindexResource); 
        $response = $result->getResponse();
        $resolutionId = $response[0]["id"];
        
        $reindexResource->getResolutionById($resolutionId);
        $result = $api->getRequestResult($reindexResource);  
        $this->assertFalse($result->hasError());    
        
        $response = $result->getResponse();
        $resolutionIdResult = $response["id"];        
        $this->assertSame($resolutionIdResult, $resolutionId);
    } 
}