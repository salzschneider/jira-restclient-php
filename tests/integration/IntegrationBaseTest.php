<?php
namespace JiraRestlib\Tests;

class IntegrationBaseTest extends \PHPUnit_Framework_TestCase
{
    protected static $jiraRestHost;
    protected static $jiraRestUsername;
    protected static $jiraRestPassword;
    protected static $foreverIssueId;
    protected static $isVerified;

    public static function setUpBeforeClass()
    {
        if(!getenv("JIRA_REST_HOST") || !getenv("JIRA_REST_USERNAME") || !getenv("JIRA_REST_PASSWORD") || !getenv("FOREVER_ISSUE_ID"))
        {
            echo("Missing environment variable. Test aborted.\n"); 
            exit(1);
        }
        
        self::$jiraRestHost     = getenv("JIRA_REST_HOST");
        self::$jiraRestUsername = getenv("JIRA_REST_USERNAME");
        self::$jiraRestPassword = getenv("JIRA_REST_PASSWORD");
        self::$foreverIssueId   = getenv("FOREVER_ISSUE_ID"); 
     
        //need ssl verification
        self::$isVerified       = false;
    }
}