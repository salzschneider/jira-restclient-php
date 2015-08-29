<?php
use Mockery as m;
use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Resources\Role\Role;
use JiraRestlib\Tests\IntegrationBaseTest;

class RoleTest extends IntegrationBaseTest
{
    public function tearDown()
    {
        m::close();
    }   
   
    public function testGetRolesTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $roleResource = new Role();

        $roleResource->getRoles();
        $result = $api->getRequestResult($roleResource);        
        $this->assertFalse($result->hasError());      
    }  
    
    public function testGetRoleByIdTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $roleResource = new Role();

        $roleResource->getRoles();
        $result = $api->getRequestResult($roleResource); 
        $response = $result->getResponse();
        $roleId = $response[0]["id"];
        
        $roleResource->getRoleById($roleId);
        $result = $api->getRequestResult($roleResource);  
        $this->assertFalse($result->hasError());    
        
        $response = $result->getResponse();
        $roleIdResult = $response["id"];        
        $this->assertSame($roleIdResult, $roleId);
    }
}