<?php
use Mockery as m;
use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Resources\IssueType\IssueType;
use JiraRestlib\Tests\IntegrationBaseTest;

class IssueTypeTest extends IntegrationBaseTest
{
    const NEW_ISSUE_TYPE_NAME = "deleteIssueType";
    const NEW_ISSUE_TYPE_DESCRIPTION = "new description";
    
    public function tearDown()
    {
        m::close();
    }   
    
    public function testGetAllIssueTypesTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);

        $issueTypeResource = new IssueType();
        $issueTypeResource->getAllIssueTypes();
        $result = $api->getRequestResult($issueTypeResource);  
        $this->assertFalse($result->hasError()); 
    } 
    
    public function testAddIssueTypeTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        
        $issueType = array("name"        => self::NEW_ISSUE_TYPE_NAME,
                           "description" => "description of new test issue type",
                           "type"        => "standard");

        $issueTypeResource = new IssueType();
        $issueTypeResource->addIssueType($issueType);
        $result = $api->getRequestResult($issueTypeResource);               
        $this->assertFalse($result->hasError()); 
        
        $response = $result->getResponse();
        
        return $response;
    }
    
    public function testAddIssueTypeFalse()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        
        //same name
        $issueType = array("name"        => self::NEW_ISSUE_TYPE_NAME,
                           "description" => "description of new test issue type",
                           "type"        => "standard");

        $issueTypeResource = new IssueType();
        $issueTypeResource->addIssueType($issueType);
        $result = $api->getRequestResult($issueTypeResource);  
        $this->assertTrue($result->hasError()); 
    }
    
    /**
     * @depends testAddIssueTypeTrue
     */
    public function testGetIssueTypeByIdTrue($newIssueType)
    {
        $issueTypeId = $newIssueType["id"];
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);

        $issueTypeResource = new IssueType();
        $issueTypeResource->getIssueTypeById($issueTypeId);
        $result = $api->getRequestResult($issueTypeResource);         
        $this->assertFalse($result->hasError()); 
        
        $response = $result->getResponse();
        $responseIssueTypeId = $response["id"];
        
        $this->assertSame($responseIssueTypeId, $issueTypeId);
    }
    
    /**
     * @depends testAddIssueTypeTrue
     */
    public function testGetIssueTypeAlternativesTrue($newIssueType)
    {
        $issueTypeId = $newIssueType["id"];
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);

        $issueTypeResource = new IssueType();
        $issueTypeResource->getIssueTypeAlternatives($issueTypeId);
        $result = $api->getRequestResult($issueTypeResource);       
        $this->assertFalse($result->hasError()); 
    } 
    
    /**
     * @depends testAddIssueTypeTrue
     */
    public function testUpdateIssueTypeByIdTrue($newIssueType)
    {
        $issueTypeId = $newIssueType["id"];
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        
        $issueType = array("description" => self::NEW_ISSUE_TYPE_DESCRIPTION);

        $issueTypeResource = new IssueType();
        $issueTypeResource->updateIssueTypeById($issueTypeId, $issueType);
        $result = $api->getRequestResult($issueTypeResource);       
        $this->assertFalse($result->hasError()); 
        
        $response = $result->getResponse();
        $responseDesc = $response["description"];                
        
        $this->assertSame($responseDesc, self::NEW_ISSUE_TYPE_DESCRIPTION);
        $this->assertNotSame($responseDesc, $newIssueType["description"]);
    }
    
     /**
     * @depends testAddIssueTypeTrue
     */
    public function testDeleteIssueTypeByIdFalse($newIssueType)
    {
        $issueTypeId = $newIssueType["id"];
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);

        $issueTypeResource = new IssueType();
        $issueTypeResource->deleteIssueTypeById($issueTypeId, "WRONG");
        $result = $api->getRequestResult($issueTypeResource);        
        $this->assertTrue($result->hasError()); 
    }
    
    /**
     * @depends testAddIssueTypeTrue
     */
    public function testDeleteIssueTypeByIdTrue($newIssueType)
    {
        $issueTypeId = $newIssueType["id"];
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);

        $issueTypeResource = new IssueType();
        $issueTypeResource->deleteIssueTypeById($issueTypeId);
        $result = $api->getRequestResult($issueTypeResource);        
        $this->assertFalse($result->hasError()); 
    }
}