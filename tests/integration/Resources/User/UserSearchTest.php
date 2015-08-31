<?php
use Mockery as m;
use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Resources\User\UserSearch;
use JiraRestlib\Tests\IntegrationBaseTest;

class UserSearchTest extends IntegrationBaseTest
{       
    public function tearDown()
    {
        m::close();
    }   
   
    public function testDoSearchUserDefaultTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $userResource = new UserSearch();

        $userResource->doSearchUser(self::$jiraRestUsername);
        $result = $api->getRequestResult($userResource);       
        $this->assertFalse($result->hasError());      
        
        $response = $result->getResponse();
        $this->assertSame(self::$jiraRestUsername, $response[0]["name"]);  
    }  
    
    public function testDoSearchUserWithParametersTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $userResource = new UserSearch();

        $userResource->doSearchUser(self::$jiraRestUsername, 1, 50, true, true);
        $result = $api->getRequestResult($userResource);       
        $this->assertFalse($result->hasError());         
        $response = $result->getResponse();
        $this->assertEmpty($response);  
    }
    
    /**
     * There is no inactive user
     */
    public function testDoSearchUserInactiveTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $userResource = new UserSearch();

        $userResource->doSearchUser(self::$jiraRestUsername, 0, 50, false, false);
        $result = $api->getRequestResult($userResource);       
        $this->assertFalse($result->hasError());      
        
        $response = $result->getResponse();
        $this->assertEmpty($response);  
    } 
}