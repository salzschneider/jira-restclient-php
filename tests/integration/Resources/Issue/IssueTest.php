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
    
    public function invalidWorklogIds()
    {
        return array(
          array(null),
          array(""),
          array(0),  
        );
    }
    
    public function invalidWorklogIdNumber()
    {
        return array(
          array(-1),
          array(1111111111111111),
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
    
    public function testGetTransitionIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new Issue();       
        $issueResource->getAllTransitionIssue(self::$foreverIssueId);
        $result = $api->getRequestResult($issueResource);     
        $this->assertFalse($result->hasError()); 
        
        //get a valid transition id without fields
        $reponse = $result->getResponse();
        $transId = $reponse['transitions'][0]['id'];
        $this->assertFalse(array_key_exists("fields", $reponse['transitions'][0])); 
        
        //test with id
        $issueResource->getAllTransitionIssue(self::$foreverIssueId, false, $transId);
        $result = $api->getRequestResult($issueResource);     
        $reponse = $result->getResponse();
        $specTransId = $reponse['transitions'][0]['id'];
        $this->assertSame($specTransId, $transId); 
        
        //test with id with fields
        $issueResource->getAllTransitionIssue(self::$foreverIssueId, true, $transId);
        $result = $api->getRequestResult($issueResource);     
        $reponse = $result->getResponse();
        $specTransId = $reponse['transitions'][0]['id'];
        $this->assertSame($specTransId, $transId); 
        
        //with fields
        $this->assertTrue(array_key_exists("fields", $reponse['transitions'][0]));                
    }
    
    public function testGetTransitionIssueFalse()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new Issue();       
        
        //invalid transaction id
        $issueResource->getAllTransitionIssue(self::$foreverIssueId, false, "WRONG");
        $result = $api->getRequestResult($issueResource);             
        $this->assertTrue($result->hasError());            
    }
    
    /**
     * @todo fix add comment: https://answers.atlassian.com/questions/171856/jira-rest-api-for-transitions-not-working
     */
    public function testPerformTransitionIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new Issue();  
                        
        //get a valid transition id without fields
        $issueResource->getAllTransitionIssue(self::$foreverIssueId, true);
        $result = $api->getRequestResult($issueResource);    
        $reponse = $result->getResponse();        
        $this->assertFalse($result->hasError());  
        $transId = $reponse['transitions'][0]['id'];
 
        //create transition array
        $data = array("update"       => array("comment"    => array(array("add" => array("body" => "Transition completed.")))),                      
                      "transition"   => array("id"         => $transId));
    
        $issueResource->performTransitionIssue(self::$foreverIssueId, $data, true);
        $result = $api->getRequestResult($issueResource);                    
        $this->assertFalse($result->hasError()); 
        
        //get a valid transition id without fields
        $issueResource->getAllTransitionIssue(self::$foreverIssueId, true);
        $result = $api->getRequestResult($issueResource);    
        $reponse = $result->getResponse();        
        $this->assertFalse($result->hasError());  
        $transId = $reponse['transitions'][0]['id'];
 
        //create transition array
        $data = array("update"       => array("comment"    => array(array("add" => array("body" => "Transition completed.")))),                      
                      "transition"   => array("id"         => $transId));
        
        $issueResource->performTransitionIssue(self::$foreverIssueId, $data, false);
        $result = $api->getRequestResult($issueResource);   
        
        $this->assertFalse($result->hasError());  
    }
    
    /**
     * @expectedException \JiraRestlib\Resources\ResourcesException
     */
    public function testPerformTransitionIssueFalse()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new Issue();                                 
 
        //create transition array
        $data = array();
    
        $issueResource->performTransitionIssue(self::$foreverIssueId, $data, true);
        $result = $api->getRequestResult($issueResource);                    
    }
    
    public function testVoteIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new Issue();                  
        
        $issueResource->voteIssue(self::$foreverIssueId);        
        $result = $api->getRequestResult($issueResource);           
        $this->assertFalse($result->hasError());        
        
        $issueResource->getVoteIssue(self::$foreverIssueId);        
        $result = $api->getRequestResult($issueResource); 
        $response = $result->getResponse();       
        $this->assertFalse($result->hasError());      
        $this->assertNotEmpty($response['voters']);
        
        $issueResource->deleteVoteIssue(self::$foreverIssueId);        
        $result = $api->getRequestResult($issueResource);         
        $this->assertFalse($result->hasError()); 
        
        $issueResource->getVoteIssue(self::$foreverIssueId);        
        $result = $api->getRequestResult($issueResource); 
        $response = $result->getResponse();
       
        $this->assertEmpty($response['voters']);
    } 
    
    public function testWatcherIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new Issue();                  
        
        $issueResource->addWatcherIssue(self::$foreverIssueId);        
        $result = $api->getRequestResult($issueResource);           
        $this->assertFalse($result->hasError());   
                
        $issueResource->getWatchersIssue(self::$foreverIssueId);        
        $result = $api->getRequestResult($issueResource); 
        $response = $result->getResponse();       
        $this->assertFalse($result->hasError());     
        $watchers = array();
        
        foreach($response['watchers'] as $watcher)
        {
            $watchers[] = $watcher['name'];
        }
   
        $this->assertContains(self::$jiraRestUsername, $watchers);
        
        $issueResource->removeWatcherIssue(self::$foreverIssueId, "jirapitest");        
        $result = $api->getRequestResult($issueResource);          
        $this->assertFalse($result->hasError()); 
        
        $issueResource->getWatchersIssue(self::$foreverIssueId);        
        $result = $api->getRequestResult($issueResource); 
        $response = $result->getResponse();
      
        $watchers = array();
        
        foreach($response['watchers'] as $watcher)
        {
            $watchers[] = $watcher['name'];
        }
   
        $this->assertNotContains(self::$jiraRestUsername, $watchers);
    } 
    
    /**
     * @expectedException \JiraRestlib\Resources\ResourcesException
     */
    public function testWatcherIssueFalse()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new Issue();                                  
        
        $issueResource->removeWatcherIssue(self::$foreverIssueId, null);        
        $result = $api->getRequestResult($issueResource);          
    }
    
    public function testGetAllWorklogIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new Issue();                  
        
        $issueResource->getAllWorklogIssue(self::$foreverIssueId);        
        $result = $api->getRequestResult($issueResource);    
        $this->assertFalse($result->hasError());        
    } 
    
    public function testAddNewWorklogIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new Issue();                  
        
        //worklog array - 1 hour worklog
        $worklog = array("timeSpentSeconds" => 3600,
                         "comment"          => "1 h work");
        
        $issueResource->addNewWorklogIssue(self::$foreverIssueId, $worklog);        
        $result = $api->getRequestResult($issueResource);       
        $this->assertFalse($result->hasError());        
    }  
    
    public function testAddWorklogNewRemainingIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new Issue();                  
        
         //worklog array - 1 hour worklog
        $worklog = array("timeSpentSeconds" => 1800,
                         "comment"          => "0.5 h work");
        
        $issueResource->addWorklogNewRemainingIssue(self::$foreverIssueId, "6 d", $worklog);        
        $result = $api->getRequestResult($issueResource);       
       
        $this->assertFalse($result->hasError());        
    } 
    
    public function testAddWorklogReduceRemainingIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new Issue();                  
        
         //worklog array - 1 hour worklog
        $worklog = array("timeSpentSeconds" => 1800,
                         "comment"          => "0.5 h work");
        
        $issueResource->addWorklogReduceRemainingIssue(self::$foreverIssueId, "1 d", $worklog);        
        $result = $api->getRequestResult($issueResource);       
       
        $this->assertFalse($result->hasError());        
    }
    
    public function testAdWorklogLeaveRemainingIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new Issue();                  
        
         //worklog array - 1 hour worklog
        $worklog = array("timeSpentSeconds" => 7200,
                         "comment"          => "2 h work");
        
        $issueResource->addWorklogLeaveRemainingIssue(self::$foreverIssueId, $worklog);        
        $result = $api->getRequestResult($issueResource);       
       
        $this->assertFalse($result->hasError());        
    }
    
    public function testSetNewEstimatedTimeIssueTrue()
    {
        $estimateTime = rand(1,4)."d";
        
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new Issue();                  
        
        $issueResource->setNewEstimatedTimeIssue(self::$foreverIssueId, $estimateTime);        
        $result = $api->getRequestResult($issueResource);   
        $this->assertFalse($result->hasError());    
        
        $issueResource->getIssue(self::$foreverIssueId , array("timetracking"), array("name"));     
        $result = $api->getRequestResult($issueResource);  
        $response = $result->getResponse();
        
        $originEst = $response["fields"]["timetracking"]["originalEstimate"];
        $this->assertSame($originEst, $estimateTime);  
    }
    
    public function testGetWorklogByIdIssueTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $issueResource = new Issue();                  
        
        //get a valid worklog id
        $issueResource->getAllWorklogIssue(self::$foreverIssueId);        
        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();
        $worklogId = $response['worklogs'][0]['id'];
        
        $issueResource->getWorklogByIdIssue(self::$foreverIssueId, $worklogId);        
        $result = $api->getRequestResult($issueResource);          
        $this->assertFalse($result->hasError());                    
    }
    
    /**
     * @dataProvider invalidWorklogIds
     * @expectedException \JiraRestlib\Resources\ResourcesException
     */
    public function testGetWorklogByIdIssueFalse($worklogId)
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $issueResource = new Issue();                  
        
        $issueResource->getWorklogByIdIssue(self::$foreverIssueId, $worklogId);        
        $result = $api->getRequestResult($issueResource);                            
    }
    
    /**
     * @dataProvider invalidWorklogIdNumber
     */
    public function testGetWorklogByIdIssueWrongNumberFalse($worklogId)
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $issueResource = new Issue();                  
        
        $issueResource->getWorklogByIdIssue(self::$foreverIssueId, $worklogId);        
        $result = $api->getRequestResult($issueResource);          
        $this->assertTrue($result->hasError());                                 
    }
    
    ///////// DELETE WORKLOG ////////////
    
    public function testDeleteWorklogIssueTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $issueResource = new Issue();                  
        
        //get a valid worklog id
        $issueResource->getAllWorklogIssue(self::$foreverIssueId);        
        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();
        $worklogId = $response['worklogs'][0]['id'];
        $count = count($response['worklogs']);
        
        $issueResource->deleteWorklogIssue(self::$foreverIssueId, $worklogId);        
        $result = $api->getRequestResult($issueResource);          
        $this->assertFalse($result->hasError()); 
        
         //get all worklog
        $issueResource->getAllWorklogIssue(self::$foreverIssueId);        
        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();
        $countNew = count($response['worklogs']);
        
        $this->assertEquals($countNew, $count-1);
    }
    
     /**
     * @dataProvider invalidWorklogIds
     * @expectedException \JiraRestlib\Resources\ResourcesException
     */
    public function testDeleteWorklogIssueFalse($worklogId)
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $issueResource = new Issue();     

        $issueResource->deleteWorklogIssue(self::$foreverIssueId, $worklogId);   
        $result = $api->getRequestResult($issueResource);   
    }
    
    public function testDeleteWorklogLeaveRemainingIssueTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $issueResource = new Issue();                  
        
        //get a valid worklog id
        $issueResource->getAllWorklogIssue(self::$foreverIssueId);        
        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();
        $worklogId = $response['worklogs'][0]['id'];
        $count = count($response['worklogs']);
        
        $issueResource->deleteWorklogLeaveRemainingIssue(self::$foreverIssueId, $worklogId);        
        $result = $api->getRequestResult($issueResource);          
        $this->assertFalse($result->hasError()); 
        
         //get all worklog
        $issueResource->getAllWorklogIssue(self::$foreverIssueId);        
        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();
        $countNew = count($response['worklogs']);
        
        $this->assertEquals($countNew, $count-1);
    }
    
     /**
     * @dataProvider invalidWorklogIds
     * @expectedException \JiraRestlib\Resources\ResourcesException
     */
    public function testDeleteWorklogLeaveRemainingIssueFalse($worklogId)
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $issueResource = new Issue();     

        $issueResource->deleteWorklogLeaveRemainingIssue(self::$foreverIssueId, $worklogId);   
        $result = $api->getRequestResult($issueResource);   
    }
    
    public function testDeleteWorklogNewRemainingIssueTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $issueResource = new Issue();                  
        
        //get a valid worklog id
        $issueResource->getAllWorklogIssue(self::$foreverIssueId);        
        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();
        $worklogId = $response['worklogs'][0]['id'];
        $count = count($response['worklogs']);
        
        $issueResource->deleteWorklogNewRemainingIssue(self::$foreverIssueId, $worklogId, "40d");        
        $result = $api->getRequestResult($issueResource);          
        $this->assertFalse($result->hasError()); 
        
         //get all worklog
        $issueResource->getAllWorklogIssue(self::$foreverIssueId);        
        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();
        $countNew = count($response['worklogs']);
        
        $this->assertEquals($countNew, $count-1);
    }
    
    /**
     * @dataProvider invalidWorklogIds
     * @expectedException \JiraRestlib\Resources\ResourcesException
     */
    public function testDeleteWorklogNewRemainingIssueFalse($worklogId)
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $issueResource = new Issue();     

        $issueResource->deleteWorklogNewRemainingIssue(self::$foreverIssueId, $worklogId, "40d");   
        $result = $api->getRequestResult($issueResource);   
    }
    
    public function testDeleteWorklogIncreaseRemainingIssueTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $issueResource = new Issue();                  
        
        //get a valid worklog id
        $issueResource->getAllWorklogIssue(self::$foreverIssueId);        
        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();
        $worklogId = $response['worklogs'][0]['id'];
        $count = count($response['worklogs']);
        
        $issueResource->deleteWorklogIncreaseRemainingIssue(self::$foreverIssueId, $worklogId, "2d");        
        $result = $api->getRequestResult($issueResource);          
        $this->assertFalse($result->hasError()); 
        
         //get all worklog
        $issueResource->getAllWorklogIssue(self::$foreverIssueId);        
        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();
        $countNew = count($response['worklogs']);
        
        $this->assertEquals($countNew, $count-1);
    }
    
     /**
     * @dataProvider invalidWorklogIds
     * @expectedException \JiraRestlib\Resources\ResourcesException
     */
    public function testDeleteWorklogIncreaseRemainingIssueFalse($worklogId)
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $issueResource = new Issue();     

        $issueResource->deleteWorklogIncreaseRemainingIssue(self::$foreverIssueId, $worklogId, "40d");   
        $result = $api->getRequestResult($issueResource);   
    }            
    
    public function testUpdateWorklogIssueTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $issueResource = new Issue();                  
        
        //get a valid worklog id
        $issueResource->getAllWorklogIssue(self::$foreverIssueId);        
        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();
        $worklogId = $response['worklogs'][0]['id'];
        
         //worklog array - 1 hour worklog
        $worklog = array("timeSpentSeconds" => 1800,
                         "comment"          => "update worklog");
        
        $issueResource->updateWorklogIssue(self::$foreverIssueId, $worklogId, $worklog);        
        $result = $api->getRequestResult($issueResource);          
        $this->assertFalse($result->hasError());         
    }
    
     /**
     * @dataProvider invalidWorklogIds
     * @expectedException \JiraRestlib\Resources\ResourcesException
     */
    public function testUpdateWorklogIssueFalse($worklogId)
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $issueResource = new Issue();     
        
        //worklog array - 1 hour worklog
        $worklog = array("timeSpentSeconds" => 1800,
                         "comment"          => "update worklog");
        
        $issueResource->updateWorklogIssue(self::$foreverIssueId, $worklogId, $worklog);   
        $result = $api->getRequestResult($issueResource);   
    }
    
    public function testUpdateWorklogLeaveRemainingIssueTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $issueResource = new Issue();                  
        
        //get a valid worklog id
        $issueResource->getAllWorklogIssue(self::$foreverIssueId);        
        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();
        $worklogId = $response['worklogs'][0]['id'];
        
         //worklog array - 1 hour worklog
        $worklog = array("timeSpentSeconds" => 12500,
                         "comment"          => "update worklog");
        
        $issueResource->updateWorklogLeaveRemainingIssue(self::$foreverIssueId, $worklogId, $worklog);        
        $result = $api->getRequestResult($issueResource);          
        $this->assertFalse($result->hasError());         
    }
    
     /**
     * @dataProvider invalidWorklogIds
     * @expectedException \JiraRestlib\Resources\ResourcesException
     */
    public function testUpdateWorklogLeaveRemainingIssueFalse($worklogId)
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $issueResource = new Issue();     
        
        //worklog array - 1 hour worklog
        $worklog = array("timeSpentSeconds" => 1800,
                         "comment"          => "update worklog");
        
        $issueResource->UpdateWorklogLeaveRemainingIssue(self::$foreverIssueId, $worklogId, $worklog);   
        $result = $api->getRequestResult($issueResource);   
    }
    
    public function testUpdateWorklogNewRemainingIssueTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $issueResource = new Issue();                  
        
        //get a valid worklog id
        $issueResource->getAllWorklogIssue(self::$foreverIssueId);        
        $result = $api->getRequestResult($issueResource);
        $response = $result->getResponse();
        $worklogId = $response['worklogs'][0]['id'];
        
         //worklog array - 1 hour worklog
        $worklog = array("timeSpentSeconds" => 4000,
                         "comment"          => "update worklog");
        
        $issueResource->updateWorklogNewRemainingIssue(self::$foreverIssueId, $worklogId, "30d", $worklog);        
        $result = $api->getRequestResult($issueResource);          
        $this->assertFalse($result->hasError());         
    }
    
     /**
     * @dataProvider invalidWorklogIds
     * @expectedException \JiraRestlib\Resources\ResourcesException
     */
    public function testUpdateWorklogNewRemainingIssueFalse($worklogId)
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $issueResource = new Issue();     
        
        //worklog array - 1 hour worklog
        $worklog = array("timeSpentSeconds" => 1800,
                         "comment"          => "update worklog");
        
        $issueResource->updateWorklogNewRemainingIssue(self::$foreverIssueId, $worklogId, "30d", $worklog);  
        $result = $api->getRequestResult($issueResource);   
    }
}