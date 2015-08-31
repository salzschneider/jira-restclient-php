<?php
use Mockery as m;
use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Resources\User\User;
use JiraRestlib\Tests\IntegrationBaseTest;

class UserTest extends IntegrationBaseTest
{    
    const TEMP_USER = "deletetempuser";
    const TEMP_USER_KEY = "deletetempuserkey";
    
    public function tearDown()
    {
        m::close();
    }   
   
    public function testGetUserWithUsernameTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $userResource = new User();

        $userResource->getUser(self::$jiraRestUsername);
        $result = $api->getRequestResult($userResource);       
        $this->assertFalse($result->hasError());      
    }
    
    public function testGetUserWithKeyTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $userResource = new User();

        $userResource->getUser(null, self::$jiraRestUsername);
        $result = $api->getRequestResult($userResource);       
        $this->assertFalse($result->hasError());      
    }
    
     /**
     * @expectedException \JiraRestlib\Resources\ResourcesException
     */
    public function testGetUserWithBothFalse()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $userResource = new User();

        $userResource->getUser(self::$jiraRestUsername, self::$jiraRestUsername);  
    }
    
    public function testCreateUserTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $userResource = new User();

        $userData = array("name"         => self::TEMP_USER,
                          "emailAddress" => "test@test.com",
                          "displayName"  => "John Doe");
        
        $userResource->createUser($userData);
        $result = $api->getRequestResult($userResource); 
        $this->assertFalse($result->hasError());      
        
        $response = $result->getResponse();
        $this->assertSame(self::TEMP_USER, $response["name"]); 
        
        $userData = array("name"         => self::TEMP_USER_KEY,
                          "emailAddress" => "testkey@test.com",
                          "displayName"  => "John Doe Key");
        
        $userResource->createUser($userData);
        $result = $api->getRequestResult($userResource); 
        $this->assertFalse($result->hasError());      
        
        $response = $result->getResponse();
        $this->assertSame(self::TEMP_USER_KEY, $response["name"]);  
    }
    
    public function testEditUserWithUsernameTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $userResource = new User();

        $newData = array("emailAddress" => "test2@test.com");
        
        $userResource->editUser($newData, self::TEMP_USER);
        $result = $api->getRequestResult($userResource);     
        $this->assertFalse($result->hasError()); 
        
        $response = $result->getResponse();
        $this->assertSame("test2@test.com", $response["emailAddress"]);   
    }
    
    public function testEditUserWithKeyTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $userResource = new User();

        $newData = array("emailAddress" => "test3@test.com");
        
        $userResource->editUser($newData, null, self::TEMP_USER);
        $result = $api->getRequestResult($userResource);     
        $this->assertFalse($result->hasError()); 
        
        $response = $result->getResponse();
        $this->assertSame("test3@test.com", $response["emailAddress"]);   
    }
    
    /**
     * @expectedException \JiraRestlib\Resources\ResourcesException
     */
    public function testEditUserWithBothFalse()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $userResource = new User();

        $newData = array("emailAddress" => "test3@test.com");
        
        $userResource->editUser($newData, self::TEMP_USER, self::TEMP_USER);
        $result = $api->getRequestResult($userResource);       
    }
    
    public function testDeleteUserWithUsernameTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $userResource = new User();
        
        $userResource->deleteUser(self::TEMP_USER);
        $result = $api->getRequestResult($userResource);     
        $this->assertFalse($result->hasError()); 
        
        $userResource->getUser(self::TEMP_USER);
        $result = $api->getRequestResult($userResource);
        $this->assertTrue($result->hasError());  
        $responseCode = $result->getResponseHttpStatusCode();        
        $this->assertSame((int)$responseCode, 404);               
    }
    
    public function testDeleteUserWithKeyTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $userResource = new User();
        
        $userResource->deleteUser(null, self::TEMP_USER_KEY);
        $result = $api->getRequestResult($userResource);     
        $this->assertFalse($result->hasError()); 
        
        $userResource->getUser(self::TEMP_USER_KEY);
        $result = $api->getRequestResult($userResource);
        $this->assertTrue($result->hasError());  
        $responseCode = $result->getResponseHttpStatusCode();        
        $this->assertSame((int)$responseCode, 404);               
    }
    
     /**
     * @expectedException \JiraRestlib\Resources\ResourcesException
     */
    public function testDeleteUserWithBothFalse()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $userResource = new User();
        
        $userResource->deleteUser(self::TEMP_USER_KEY, self::TEMP_USER_KEY);
        $result = $api->getRequestResult($userResource);                  
    }
}