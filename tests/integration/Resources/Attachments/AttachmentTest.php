<?php
use Mockery as m;
use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
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

}