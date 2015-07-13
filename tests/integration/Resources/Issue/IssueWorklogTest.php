<?php
use Mockery as m;
use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Resources\Issue\IssueWorklog;
use JiraRestlib\Tests\IntegrationBaseTest;

class IssueWorklogTest extends IntegrationBaseTest
{
    public function tearDown()
    {
        m::close();
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
   
    public function testGetAllWorklogIssueTrue()
    {
        $defaultOption = array("auth"      => array(self::$jiraRestUsername, self::$jiraRestPassword),
                               "verify"    => self::$isVerified);

        $config = new Config(self::$jiraRestHost);
        $config->addRequestConfigArray($defaultOption);   

        $api = new Api($config);
        $issueResource = new IssueWorklog();                  
        
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
        $issueResource = new IssueWorklog();                  
        
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
        $issueResource = new IssueWorklog();                  
        
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
        $issueResource = new IssueWorklog();                  
        
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
        $issueResource = new IssueWorklog();             
        
         //worklog array - 1 hour worklog
        $worklog = array("timeSpentSeconds" => 7200,
                         "comment"          => "2 h work");
        
        $issueResource->addWorklogLeaveRemainingIssue(self::$foreverIssueId, $worklog);        
        $result = $api->getRequestResult($issueResource);       
       
        $this->assertFalse($result->hasError());        
    }
    
       public function testGetWorklogByIdIssueTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $issueResource = new IssueWorklog();                  
        
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
        $issueResource = new IssueWorklog();                  
        
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
        $issueResource = new IssueWorklog();                  
        
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
        $issueResource = new IssueWorklog();                  
        
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
        $issueResource = new IssueWorklog();     

        $issueResource->deleteWorklogIssue(self::$foreverIssueId, $worklogId);   
        $result = $api->getRequestResult($issueResource);   
    }
    
    public function testDeleteWorklogLeaveRemainingIssueTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $issueResource = new IssueWorklog();                  
        
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
        $issueResource = new IssueWorklog();     

        $issueResource->deleteWorklogLeaveRemainingIssue(self::$foreverIssueId, $worklogId);   
        $result = $api->getRequestResult($issueResource);   
    }
    
    public function testDeleteWorklogNewRemainingIssueTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $issueResource = new IssueWorklog();                  
        
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
        $issueResource = new IssueWorklog();     

        $issueResource->deleteWorklogNewRemainingIssue(self::$foreverIssueId, $worklogId, "40d");   
        $result = $api->getRequestResult($issueResource);   
    }
    
    public function testDeleteWorklogIncreaseRemainingIssueTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $issueResource = new IssueWorklog();                  
        
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
        $issueResource = new IssueWorklog();     

        $issueResource->deleteWorklogIncreaseRemainingIssue(self::$foreverIssueId, $worklogId, "40d");   
        $result = $api->getRequestResult($issueResource);   
    }            
    
    public function testUpdateWorklogIssueTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $issueResource = new IssueWorklog();                  
        
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
        $issueResource = new IssueWorklog();     
        
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
        $issueResource = new IssueWorklog();                  
        
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
        $issueResource = new IssueWorklog();     
        
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
        $issueResource = new IssueWorklog();                  
        
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
        $issueResource = new IssueWorklog();     
        
        //worklog array - 1 hour worklog
        $worklog = array("timeSpentSeconds" => 1800,
                         "comment"          => "update worklog");
        
        $issueResource->updateWorklogNewRemainingIssue(self::$foreverIssueId, $worklogId, "30d", $worklog);  
        $result = $api->getRequestResult($issueResource);   
    }
}