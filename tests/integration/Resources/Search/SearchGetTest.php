<?php
use Mockery as m;
use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Resources\Search\Search;
use JiraRestlib\Tests\IntegrationBaseTest;

class SearchGetTest extends IntegrationBaseTest
{

    public function tearDown()
    {
        m::close();
    }   
   
    public function testDoSearchGetDefaultTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $searchResource = new Search();

        $jql = "";        
        $searchResource->doSearchGet($jql);
        $result = $api->getRequestResult($searchResource);     
        $this->assertFalse($result->hasError());  
        
        $response = $result->getResponse();
        $issues = $response['issues'];        
        $this->assertGreaterThanOrEqual(1, count($issues));
        $secondIssueId = $issues[1]["id"];
        
        return $secondIssueId;
    }  
    
    public function testDoSearchGetDefaultFalse()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $searchResource = new Search();

        $jql = "WRONG";        
        $searchResource->doSearchGet($jql);
        $result = $api->getRequestResult($searchResource);     
        $this->assertTrue($result->hasError()); 
    }  
    
    public function testDoSearchGetTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $searchResource = new Search();

        $jql = "issueKey = ".self::$foreverIssueId;
        
        $searchResource->doSearchGet($jql);
        $result = $api->getRequestResult($searchResource);  
        $this->assertFalse($result->hasError());     
        
        $response = $result->getResponse();
        $issues = $response['issues'];        
        $this->assertSame(count($issues), 1);        
    }
    
    /**
     * @depends testDoSearchGetDefaultTrue
     */
    public function testDoSearchGetStartAtTrue($secondIssueId)
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $searchResource = new Search();

        $jql = "";   
        $startAt = 1;
       
        $searchResource->doSearchGet($jql, $startAt);
        $result = $api->getRequestResult($searchResource);  
        $this->assertFalse($result->hasError());     
        
        $response = $result->getResponse();
        $issues = $response['issues'];        
        $this->assertSame($issues[0]['id'], $secondIssueId);        
    }

    public function testDoSearchGetMaxResultTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $searchResource = new Search();

        $jql = "";   
        $startAt = 0;
        $maxResult = 2;
       
        $searchResource->doSearchGet($jql, $startAt, $maxResult);
        $result = $api->getRequestResult($searchResource);  
        $this->assertFalse($result->hasError());     
        
        $response = $result->getResponse();
        $issues = $response['issues'];   
   
        $this->assertSame(count($issues), $maxResult);        
    }
    
    public function testDoSearchGetValidateTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $searchResource = new Search();

        $jql = "issueKey = sdsd";
        
        $startAt = 0;
        $maxResult = 50;

        $searchResource->doSearchGet($jql, $startAt, $maxResult, true);
        $result = $api->getRequestResult($searchResource);       
        $this->assertTrue($result->hasError());        
        
        $searchResource->doSearchGet($jql, $startAt, $maxResult, false);
        $result = $api->getRequestResult($searchResource);       
        $this->assertFalse($result->hasError());   
    }
    
    public function testDoSearchGetFieldsTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $searchResource = new Search();

        $jql = "";
        $startAt = 0;
        $maxResult = 50;

        $searchResource->doSearchGet($jql, $startAt, $maxResult, true, array("summary", "comment"));
        $result = $api->getRequestResult($searchResource);         
        $this->assertFalse($result->hasError());        
        
        $response = $result->getResponse();
        $fields = array_keys($response["issues"][0]["fields"]);
        $diff = array_diff($fields, array("summary", "comment"));
        $diff2 = array_diff(array("summary", "comment"), $fields);
      
        $this->assertTrue(empty($diff));
        $this->assertTrue(empty($diff2));
        
    }
    
    public function testDoSearchGetExpandTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $searchResource = new Search();

        $jql = "";
        $startAt = 0;
        $maxResult = 2;

        $searchResource->doSearchGet($jql, $startAt, $maxResult, true, array(), array("names", "schema"));
        $result = $api->getRequestResult($searchResource);   
        $this->assertFalse($result->hasError());    
        
        $response = $result->getResponse();
   
        $this->assertArrayHasKey("names", $response); 
        $this->assertArrayHasKey("schema", $response); 
    }
}