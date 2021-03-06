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
   
    public function testGetResolutionsTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $resolutionResource = new Resolution();

        $resolutionResource->getResolutions();
        $result = $api->getRequestResult($resolutionResource);  
        $this->assertFalse($result->hasError());      
    }  
    
     public function testGetResolutionByIdTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $resolutionResource = new Resolution();

        $resolutionResource->getResolutions();
        $result = $api->getRequestResult($resolutionResource); 
        $response = $result->getResponse();
        $resolutionId = $response[0]["id"];
        
        $resolutionResource->getResolutionById($resolutionId);
        $result = $api->getRequestResult($resolutionResource);  
        $this->assertFalse($result->hasError());    
        
        $response = $result->getResponse();
        $resolutionIdResult = $response["id"];        
        $this->assertSame($resolutionIdResult, $resolutionId);
    } 
}