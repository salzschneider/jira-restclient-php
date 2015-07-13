<?php
use Mockery as m;
use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Resources\Issue\IssueComment;
use JiraRestlib\Tests\IntegrationBaseTest;

class IssueCommentTest extends IntegrationBaseTest
{
    public function tearDown()
    {
        m::close();
    }   
   
    public function testAddCommentIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new IssueComment();     
        
        //create comment
        $comment = array("body"       => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eget venenatis elit.",
                         "visibility" => array("type"  => "role",
                                               "value" => "Users"));
        
        $issueResource->addCommentIssue(self::$foreverIssueId, $comment);        
        $result = $api->getRequestResult($issueResource);            
        $this->assertFalse($result->hasError()); 
        
        $response = $result->getResponse();
        $this->assertFalse(array_key_exists("renderedBody", $response)); 
        
        $issueResource->addCommentIssue(self::$foreverIssueId, $comment, true);        
        $result = $api->getRequestResult($issueResource);            
        $this->assertFalse($result->hasError()); 
        
        $response = $result->getResponse();
        $this->assertTrue(array_key_exists("renderedBody", $response)); 
    }
    
    public function testGetAllCommentsIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new IssueComment();       
        $issueResource->getAllCommentsIssue(self::$foreverIssueId, false);        
        $result = $api->getRequestResult($issueResource);
        $this->assertFalse($result->hasError()); 
        
        $response = $result->getResponse();
        $comments = $response["comments"];
        $this->assertFalse(array_key_exists("renderedBody", $comments[0])); 
        
        //with rendered body
        $issueResource->getAllCommentsIssue(self::$foreverIssueId, true);       
        $result = $api->getRequestResult($issueResource);        
        $this->assertFalse($result->hasError());
        
        $response = $result->getResponse();
        $comments = $response["comments"];
        $this->assertTrue(array_key_exists("renderedBody", $comments[0])); 
    }          
    
    public function testAddCommentIssueFalse()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new IssueComment();     
        
        //create comment
        $comment = array("WRONG" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eget venenatis elit.");
        
        $issueResource->addCommentIssue(self::$foreverIssueId, $comment);        
        $result = $api->getRequestResult($issueResource);            
        $this->assertTrue($result->hasError()); 
    } 
    
    public function testGetCommentByIdIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new IssueComment();  
        
        //get all comment
        $issueResource->getAllCommentsIssue(self::$foreverIssueId, false);
        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();
        
        //get the fist comment
        $commentId = $response["comments"][0]['id'];
          
        $issueResource->getCommentByIdIssue(self::$foreverIssueId, $commentId, true);        
        $result = $api->getRequestResult($issueResource);      
        $this->assertFalse($result->hasError());
        
        $response = $result->getResponse();
        $this->assertTrue(array_key_exists("renderedBody", $response)); 
        
        $issueResource->getCommentByIdIssue(self::$foreverIssueId, $commentId);        
        $result = $api->getRequestResult($issueResource);      
        $this->assertFalse($result->hasError());
        
        $response = $result->getResponse();
        $this->assertFalse(array_key_exists("renderedBody", $response)); 
    }
    
    public function testUpdateCommentByIdIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new IssueComment();   
        
        //get all comment
        $issueResource->getAllCommentsIssue(self::$foreverIssueId, false);
        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();
        
        //get the fist comment
        $commentId = $response["comments"][0]['id'];
        
        //create comment
        $comment = array("body"       => "Update lorem ipsum",
                         "visibility" => array("type"  => "role",
                                               "value" => "Users"));
        
        $issueResource->updateCommentByIdIssue(self::$foreverIssueId, $commentId, $comment);        
        $result = $api->getRequestResult($issueResource);            
          
        $this->assertFalse($result->hasError()); 
        
        $response = $result->getResponse();
        $this->assertFalse(array_key_exists("renderedBody", $response)); 
        
        $issueResource->updateCommentByIdIssue(self::$foreverIssueId, $commentId, $comment, true);        
        $result = $api->getRequestResult($issueResource);            
        $this->assertFalse($result->hasError()); 
        
        $response = $result->getResponse();
        $this->assertTrue(array_key_exists("renderedBody", $response)); 
    } 
    
    public function testUpdateCommentByIdIssueFalse()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new IssueComment();                  
        
        //create comment
        $comment = array("body"       => "Update lorem ipsum",
                         "visibility" => array("type"  => "role",
                                               "value" => "Users"));
        
        $issueResource->updateCommentByIdIssue(self::$foreverIssueId, "WRONG", $comment);        
        $result = $api->getRequestResult($issueResource);            
          
        $this->assertTrue($result->hasError());        
    } 
    
    public function testDeleteCommentByIdIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new IssueComment();                  
        
        //get all comment
        $issueResource->getAllCommentsIssue(self::$foreverIssueId, false);
        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();
        
        //get the fist comment
        $commentId = $response["comments"][0]['id'];
        
        $issueResource->deleteCommentByIdIssue(self::$foreverIssueId, $commentId);        
        $result = $api->getRequestResult($issueResource);            
        $this->assertFalse($result->hasError());        
    }        
}