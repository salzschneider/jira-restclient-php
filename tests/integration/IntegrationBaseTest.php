<?php
namespace JiraRestlib\Tests;

class IntegrationBaseTest extends \PHPUnit_Framework_TestCase
{
    protected static $jiraRestHost;
    protected static $jiraRestUsername;
    protected static $jiraRestPassword;
    protected static $foreverIssueId;

    public static function setUpBeforeClass()
    {
        self::$jiraRestHost     = getenv("JIRA_REST_HOST");
        self::$jiraRestUsername = getenv("JIRA_REST_USERNAME");
        self::$jiraRestPassword = getenv("JIRA_REST_PASSWORD");
        self::$foreverIssueId   = getenv("FOREVER_ISSUE_ID");        
    }
}