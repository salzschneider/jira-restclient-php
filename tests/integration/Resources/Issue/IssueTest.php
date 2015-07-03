<?php
use Mockery as m;
use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Resources\Issue\Issue;
use JiraRestlib\Tests\IntegrationBaseTest;

class ApiIssueTest extends IntegrationBaseTest
{
    public function invalidName()
    {
        return array(
          array("-1"),
          array(-1),
          array(null),            
        );
    }
    
    public function tearDown()
    {
        m::close();
    }   
    
    public function testGetIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);

        $api = new Api($config);
        $issueResource = new Issue();
        $issueResource->getIssue(self::$foreverIssueId , array("updated", "status"), array("name", "schema"));

        $result = $api->getRequestResult($issueResource);
        
        $this->assertFalse($result->hasError()); 
    }
    
    public function testGetIssueArrayResponseTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);
        $config->addCommonConfig(Config::RESPONSE_FORMAT, \JiraRestlib\Result\ResultAbstract::RESPONSE_FORMAT_ARRAY);

        $api = new Api($config);
        $issueResource = new Issue();
        $issueResource->getIssue(self::$foreverIssueId, array("updated", "status"), array("name", "schema"));

        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();
        
        $this->assertSame(self::$foreverIssueId, $response['key']); 
        $this->assertSame(\JiraRestlib\Result\ResultAbstract::RESPONSE_FORMAT_ARRAY, $result->getFormat()); 

        //header contains these indexes
        $this->assertEmpty(array_diff(array('Date', 'Server', 'Cache-Control' ), array_keys($result->getResponseHeaders())));        
        $this->assertFalse($result->hasError()); 
    }

    public function testCreateIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);

        $api = new Api($config);
        $issueResource = new Issue();
        
        //create issue
        $issue = array("fields" => array("project"     => array("id" => "11100"),
                                  "summary"      => "Ide a summary",
                                  "issuetype"    => array("id" => "3"),
                                  "priority"     => array("id" => "2"),
                                  "description"  => "some description here",));

        $issueResource->createIssue($issue);
        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();
        
        $parentId = $response['id'];
        $this->assertFalse($result->hasError()); 
        
        //create subissue
        $issue = array("fields" => array("project"     => array("id" => "11100"),
                                    "summary"      => "Ide a summary",
                                    "issuetype"    => array("id" => "5"),
                                    "parent"       => array("id" => $parentId),
                                    "priority"     => array("id" => "2"),
                                    "description"  => "some description here",));

        $issueResource->createIssue($issue);
        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();    
        $this->assertFalse($result->hasError()); 
        
        //edit issue
        $updateFieldValues = array("update" => array("summary" => array(array("set" => "Edited summary")),
                                                     "labels"  => array(array("add" => "tag1"),
                                                                        array("remove" => "tag1"),
                                                                        array("add" => "tag2"))));

        $issueResource->editIssue($response['id'], $updateFieldValues);
        $result = $api->getRequestResult($issueResource);
        $this->assertFalse($result->hasError());             
        
        //delete subissue
        $issueResource->deleteIssue($response['id'], true);
        $result = $api->getRequestResult($issueResource);
        $this->assertFalse($result->hasError());       
    }
    
    public function testCreateIssueBulkTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);

        $api = new Api($config);
        $issueResource = new Issue();

        $issue1 = array("fields" => array("project"     => array("id" => "11100"),
                                          "summary"     => "Issue 1 Bulk",
                                          "issuetype"   => array("id" => "3"),
                                          "priority"    => array("id" => "2"),
                                          "description" => "some description here, bulk 2"));

        $issue2 = array("fields" => array("project"     => array("id" => "11100"),
                                          "summary"     => "Issue 2 Bulk",
                                          "issuetype"   => array("id" => "3"),
                                          "priority"    => array("id" => "2"),
                                          "description" => "some description here, bulk 2"));

        $issueList = array("issueUpdates" => array($issue1, $issue2));
        $issueResource->createIssueBulk($issueList);
        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();
        $deleteId = $response['issues'][0]['id'];
     
        $this->assertFalse($result->hasError());  
        
        //delete issue
        $issueResource->deleteIssue($deleteId);
        $result = $api->getRequestResult($issueResource);
        $this->assertFalse($result->hasError());            
    }
            
    public function testCreateIssueWrongAttribTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);

        $api = new Api($config);
        $issueResource = new Issue();
        $issue = array("fields" => array("project"     => array("id" => "11100"),
                                  "summary"      => "Ide a summary",
                                  "issuetype"    => array("id" => "3"),
                                  "priority"     => array("id" => "2"),
                                  "description"  => "some description here",
                                  "WRONG_ATTRIB" => array("id" => "2"),));

        $issueResource->createIssue($issue);
        $result = $api->getRequestResult($issueResource);
        
        $errors = $result->getErrors();
        $this->assertContains("WRONG_ATTRIB", $errors['WRONG_ATTRIB']);                 
    }
    
    public function testGetCreateMetaTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new Issue();       
        $issueResource->createMetadata(array(), array("JIR"), array("3"), array(), true);

        $result = $api->getRequestResult($issueResource);     
        $this->assertFalse($result->hasError()); 
    }
    
    public function testAddAssigneeIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new Issue();       
        $issueResource->addAssigneeIssue(self::$foreverIssueId, self::$jiraRestUsername);

        $result = $api->getRequestResult($issueResource);        
        $this->assertFalse($result->hasError()); 
    } 
    
     /**
     * @dataProvider invalidName
     * @expectedException \JiraRestlib\Resources\ResourcesException
     */
    public function testAddAssigneeIssueFalse($name)
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new Issue();       
        $issueResource->addAssigneeIssue(self::$foreverIssueId, $name);

        $result = $api->getRequestResult($issueResource);        
    }  
    
    public function testAddAssigneeIssueParamFalse()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new Issue();       
        $issueResource->addAssigneeIssue(self::$foreverIssueId, "WRONG");

        $result = $api->getRequestResult($issueResource);  
        $this->assertTrue($result->hasError()); 
    }  
    
    public function testRemoveAssigneeIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new Issue();       
        $issueResource->removeAssigneeIssue(self::$foreverIssueId);

        $result = $api->getRequestResult($issueResource);     

        $this->assertFalse($result->hasError()); 
    }
    
    public function testAddAutoAssigneeIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new Issue();       
        $issueResource->addAutoAssigneeIssue(self::$foreverIssueId);
        
        $result = $api->getRequestResult($issueResource);             
        $this->assertFalse($result->hasError()); 
    } 
    
    public function testGetAllCommentsIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new Issue();       
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
    
    public function testAddCommentIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new Issue();     
        
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
    
    public function testAddCommentIssueFalse()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new Issue();     
        
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
        $issueResource = new Issue();  
        
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
        $issueResource = new Issue();   
        
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
        $issueResource = new Issue();                  
        
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
        $issueResource = new Issue();                  
        
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
    
    public function testEditMetaIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new Issue();                  
        
        $issueResource->editMetadIssue(self::$foreverIssueId);        
        $result = $api->getRequestResult($issueResource);  
       
        $this->assertFalse($result->hasError());        
    }         
}