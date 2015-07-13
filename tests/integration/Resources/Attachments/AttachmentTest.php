<?php
use Mockery as m;
use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Resources\Issue\Issue;
use JiraRestlib\Resources\Attachments\Attachments;
use JiraRestlib\Tests\IntegrationBaseTest;

class AttachmentTest extends IntegrationBaseTest
{

    public function tearDown()
    {
        m::close();
    }   
    
    public function testAddAttachmentTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);

        $api = new Api($config);
        $attachmentResource = new Attachments();

        //valid filenames with path
        $files = array(__DIR__."/files/jira.jpg",
                       __DIR__."/files/a.pdf",);

        $attachmentResource->addAttachment(self::$foreverIssueId, $files);

        $result = $api->getRequestResult($attachmentResource);

        $this->assertFalse($result->hasError()); 
    }
    
    /**
     * @expectedException \JiraRestlib\Resources\ResourcesException
     */
    public function testAddAttachmentFalse()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);

        $api = new Api($config);
        $attachmentResource = new Attachments();

        //invalid filename set
        $files = array();

        $attachmentResource->addAttachment(self::$foreverIssueId, $files);

        $result = $api->getRequestResult($attachmentResource);

        $this->assertFalse($result->hasError()); 
    }
    
    public function testDeleteAttachmentTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);

        $api = new Api($config);
        $attachmentResource = new Attachments();
        
        $issueResource = new Issue();
        $issueResource->getIssue(self::$foreverIssueId);
        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();
        $attachmentId = $response['fields']['attachment'][0]['id']; 
        $attachmentCount = count($response['fields']['attachment']);

        $attachmentResource->deleteAttachment($attachmentId);
        $result = $api->getRequestResult($attachmentResource);     
        $this->assertFalse($result->hasError()); 
     
        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();
        $attachmentCountNew = count($response['fields']['attachment']);

        $this->assertSame($attachmentCountNew, $attachmentCount-1);
    }
    
    public function testDeleteAttachmentFalse()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);

        $api = new Api($config);
        $attachmentResource = new Attachments();

        $attachmentResource->deleteAttachment(0);
        $result = $api->getRequestResult($attachmentResource);     
        $this->assertTrue($result->hasError()); 
    }
    
    public function testGetAttachmentMetaByIdTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);

        $api = new Api($config);
        $attachmentResource = new Attachments();
        
        $issueResource = new Issue();
        $issueResource->getIssue(self::$foreverIssueId);
        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();
        $attachmentId = $response['fields']['attachment'][0]['id']; 

        $attachmentResource->getAttachmentMetaById($attachmentId);
        $result = $api->getRequestResult($attachmentResource);        
        $this->assertFalse($result->hasError()); 
    }
    
    public function testGetAttachmentMetaByIdFalse()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);

        $api = new Api($config);
        $attachmentResource = new Attachments();
        $attachmentResource->getAttachmentMetaById(0);
        $result = $api->getRequestResult($attachmentResource);        
        $this->assertTrue($result->hasError()); 
    }
    
    public function testGetAttachmentMetaTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);

        $api = new Api($config);
        $attachmentResource = new Attachments();

        $attachmentResource->getAttachmentMeta();
        $result = $api->getRequestResult($attachmentResource);    
        
        $this->assertFalse($result->hasError()); 
    }
    
    public function testGetAttachmentExpandRawTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);

        $api = new Api($config);
        $attachmentResource = new Attachments();
        
        $issueResource = new Issue();
        $issueResource->getIssue(self::$foreverIssueId);
        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();
        $attachmentId = $response['fields']['attachment'][0]['id']; 

        $attachmentResource->getAttachmentExpandRaw($attachmentId);
        $result = $api->getRequestResult($attachmentResource);  
        $this->assertFalse($result->hasError()); 
    }
    
    public function testGetAttachmentExpandRawFalse()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);

        $api = new Api($config);
        $attachmentResource = new Attachments();

        $attachmentResource->getAttachmentExpandRaw(0);
        $result = $api->getRequestResult($attachmentResource);  
        $this->assertTrue($result->hasError()); 
    }
    
    public function testGetAttachmentExpandHumanTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);

        $api = new Api($config);
        $attachmentResource = new Attachments();
        
        $issueResource = new Issue();
        $issueResource->getIssue(self::$foreverIssueId);
        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();
        $attachmentId = $response['fields']['attachment'][0]['id']; 

        $attachmentResource->getAttachmentExpandHuman($attachmentId);
        $result = $api->getRequestResult($attachmentResource);        
        $this->assertFalse($result->hasError()); 
    }
    
    public function testGetAttachmentExpandHumanFalse()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);

        $api = new Api($config);
        $attachmentResource = new Attachments();

        $attachmentResource->getAttachmentExpandHuman(0);
        $result = $api->getRequestResult($attachmentResource);  
        $this->assertTrue($result->hasError()); 
    }

}