<?php
namespace JiraRestlib\Tests;

class UnitBaseTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        echo PHP_EOL."Unit testing started.".PHP_EOL;
    }
    
    public static function tearDownAfterClass()
    {
        echo PHP_EOL."Unit testing ended.".PHP_EOL;
    }
}