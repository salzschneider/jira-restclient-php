<?php
use Mockery as m;
use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Resources\Comment\Comment;
use JiraRestlib\Resources\Issue\IssueComment;
use JiraRestlib\Tests\IntegrationBaseTest;

class CommentTest extends IntegrationBaseTest
{
    const PROPERTY_KEY = "testPropertyKey";
    const PROPERTY_VALUE = "testPropertyValue";    
    
    public function tearDown()
    {
        m::close();
    }          
    
    public function testGetCommentPropertyKeysTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);

        $api = new Api($config);
        
        //need at least one comment
        $comment = array("body"       => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eget venenatis elit.",
                         "visibility" => array("type"  => "role",
                                               "value" => "Users"));
        
        $issueResource = new IssueComment();  
        $issueResource->addCommentIssue(self::$foreverIssueId, $comment);        
        $result = $api->getRequestResult($issueResource);            
        $this->assertFalse($result->hasError()); 
             
        $issueResource->getAllCommentsIssue(self::$foreverIssueId, false);        
        $result = $api->getRequestResult($issueResource);      
        $this->assertFalse($result->hasError()); 
        
        $response = $result->getResponse();
        $commentId = $response["comments"][0]['id'];
        
        $commentResource = new Comment();
        $commentResource->getCommentPropertyKeys($commentId);
        $result = $api->getRequestResult($commentResource);       
        $this->assertFalse($result->hasError()); 
        
        return $commentId;
    }  
    
    public function testGetCommentPropertiesFalse()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $commentResource = new Comment();
        $commentResource->getCommentPropertyKeys("WRONG");
        $result = $api->getRequestResult($commentResource);       
        $this->assertTrue($result->hasError());    
    }
    
    /**
     * @depends testGetCommentPropertyKeysTrue
     */
    public function testSetCommentPropertyTrue($commentId)
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);        
        $commentResource = new Comment();
        $commentResource->setCommentProperty($commentId, self::PROPERTY_KEY, self::PROPERTY_VALUE);
        $result = $api->getRequestResult($commentResource);              
        $this->assertFalse($result->hasError());   
    }
    
    /**
     * @depends testGetCommentPropertyKeysTrue
     */
    public function testGetCommentPropertyValueByKeyTrue($commentId)
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $commentResource = new Comment();
        $commentResource->getCommentPropertyValueByKey($commentId, self::PROPERTY_KEY);
        $result = $api->getRequestResult($commentResource); 
        $response = $result->getResponse();
        $this->assertFalse($result->hasError());   
        $this->assertSame(self::PROPERTY_KEY, $response["key"]);   
        $this->assertSame(self::PROPERTY_VALUE, $response["value"]);       
    }
    
    /**
     * @depends testGetCommentPropertyKeysTrue
     */
    public function testDeleteCommentPropertyyTrue($commentId)
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $commentResource = new Comment();
        $commentResource->deleteCommentProperty($commentId, self::PROPERTY_KEY);
        $result = $api->getRequestResult($commentResource);         
        $this->assertFalse($result->hasError());   
        
        $commentResource->getCommentPropertyKeys($commentId);
        $result = $api->getRequestResult($commentResource);
        $response = $result->getResponse();
        $this->assertEmpty($response["keys"]);
    }
    
    
}