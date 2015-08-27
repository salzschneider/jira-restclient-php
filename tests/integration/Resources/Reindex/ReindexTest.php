<?php
use Mockery as m;
use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Resources\Reindex\Reindex;
use JiraRestlib\Tests\IntegrationBaseTest;

class ReindexTest extends IntegrationBaseTest
{
    public function tearDown()
    {
        m::close();
    }   
   
    public function testGetReindexSystemTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $reindexResource = new Reindex();

        $reindexResource->getReindexSystem();
        $result = $api->getRequestResult($reindexResource);  
        $response = $result->getResponse();
        $this->assertFalse($result->hasError());  
  
        //get latest reindex task id 
        $prgressUrlArray = explode("taskId=" ,$response["progressUrl"]);
        $taskId = $prgressUrlArray[1];    
        
        $reindexResource->getReindexSystem($taskId);
        $result = $api->getRequestResult($reindexResource);  
        $response = $result->getResponse();
        $this->assertFalse($result->hasError());          
    }
    
    public function testGetReindexProgressTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $reindexResource = new Reindex();

        $reindexResource->getReindexProgress();
        $result = $api->getRequestResult($reindexResource);  
        $response = $result->getResponse();
        $this->assertFalse($result->hasError());  

        //get latest reindex task id 
        $prgressUrlArray = explode("taskId=" ,$response["progressUrl"]);
        $taskId = $prgressUrlArray[1];    
      
        $reindexResource->getReindexSystem($taskId);
        $result = $api->getRequestResult($reindexResource);          
        $this->assertFalse($result->hasError());  
    }
    
    public function testDoReindexIssueTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $reindexResource = new Reindex();

        $reindexResource->doReindexIssue(self::$foreverIssueId);
        $result = $api->getRequestResult($reindexResource);      
        $this->assertFalse($result->hasError()); 
    }
    
    public function testDoReindexIssueWithParametersTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $reindexResource = new Reindex();

        $reindexResource->doReindexIssue(self::$foreverIssueId, true, true, true);
        $result = $api->getRequestResult($reindexResource);           
        $this->assertFalse($result->hasError()); 
    }
    
    public function testDoReindexRequestTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $reindexResource = new Reindex();

        $reindexResource->doReindexRequest();
        $result = $api->getRequestResult($reindexResource);  
      
        $this->assertFalse($result->hasError());  
    }
    
    /*public function testGetReindexRequestTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $reindexResource = new Reindex();
        
        $reindexResource->getReindexSystem();
        $result = $api->getRequestResult($reindexResource);  
        $response = $result->getResponse();
  
        //get latest reindex task id 
        $prgressUrlArray = explode("taskId=" ,$response["progressUrl"]);
        $requestId = $prgressUrlArray[1];    
        
        $reindexResource->getReindexRequest($requestId);
        $result = $api->getRequestResult($reindexResource);          
        $this->assertFalse($result->hasError());         
    }*/
    
    public function testGetReindexRequestBulkTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $reindexResource = new Reindex();
        
        $reindexResource->getReindexSystem();
        $result = $api->getRequestResult($reindexResource);  
        $response = $result->getResponse();
    
        //get latest reindex task id 
        $prgressUrlArray = explode("taskId=" ,$response["progressUrl"]);
        $requestId = $prgressUrlArray[1];    
        
        $reindexResource->getReindexRequestBulk(array($requestId));
        $result = $api->getRequestResult($reindexResource);
        $this->assertFalse($result->hasError());         
    }    
        
    public function testDoReindexSystemTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $reindexResource = new Reindex();

        $reindexResource->doReindexSystem();
        $result = $api->getRequestResult($reindexResource);  
        $response = $result->getResponse(); 
        
        if($result->hasError())
        {
            $this->assertTrue(!empty($response["currentSubTask"]));
            
            //waiting for finishing reindexing
            sleep(10);
        }
        else
        {
            $this->assertFalse($result->hasError()); 
        }        

        $response = $result->getResponse();
        $type = $response["type"];        
        $this->assertSame($type, "BACKGROUND");
    }
}