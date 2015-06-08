<?php
namespace JiraRestlib\Tests;

class ApiBaseTest extends \PHPUnit_Framework_TestCase
{
    protected static $jiraRestHost;
    protected static $jiraRestUsername;
    protected static $jiraRestPassword;

    public static function setUpBeforeClass()
    {
        self::$jiraRestHost = getenv("JIRA_REST_HOST");
        self::$jiraRestUsername = getenv("JIRA_REST_USERNAME");
        self::$jiraRestPassword = getenv("JIRA_REST_PASSWORD");
        
        echo PHP_EOL."Integration testing started.".PHP_EOL;
    }
    
    public static function tearDownAfterClass()
    {
        echo PHP_EOL."Integration testing ended.".PHP_EOL;
    }
}