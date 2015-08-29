<?php
use Mockery as m;
use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Resources\Status\Status;
use JiraRestlib\Tests\IntegrationBaseTest;

class StatusTest extends IntegrationBaseTest
{
    public function tearDown()
    {
        m::close();
    }   
   
    public function testGetStatusesTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $statusResource = new Status();

        $statusResource->getStatuses();
        $result = $api->getRequestResult($statusResource);      
        $this->assertFalse($result->hasError());      
    }  
    
    public function testGetStatusByIdTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $statusResource = new Status();

        $statusResource->getStatuses();
        $result = $api->getRequestResult($statusResource); 
        $response = $result->getResponse();
        $statusId = $response[0]["id"];
        
        $statusResource->getStatusById($statusId);
        $result = $api->getRequestResult($statusResource);  
        $this->assertFalse($result->hasError());    
        
        $response = $result->getResponse();
        $statusIdResult = $response["id"];               
        $this->assertSame($statusIdResult, $statusId);
    } 
    
    public function testGetStatusCategoriesTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $statusResource = new Status();

        $statusResource->getStatusCategories();
        $result = $api->getRequestResult($statusResource);            
        $this->assertFalse($result->hasError());      
    }
    
    public function testGetStatusCategoryByIdTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $statusResource = new Status();

        $statusResource->getStatusCategories();
        $result = $api->getRequestResult($statusResource); 
        $response = $result->getResponse();
        $statusCatId = $response[0]["id"];
        
        $statusResource->getStatusCategoryById($statusCatId);
        $result = $api->getRequestResult($statusResource);  
        $this->assertFalse($result->hasError());         
        $response = $result->getResponse();
        $statusCatIdResult = $response["id"];               
        $this->assertSame($statusCatIdResult, $statusCatId);
    }
}