<?php

use Mockery as m;
use JiraRestlib\Config\Config;

class ConfigTest extends UnitBaseTest
{

    public function tearDown()
    {
        m::close();
    }   
    
    /**
     * @expectedException \JiraRestlib\Config\ConfigException
     */
    public function testCreateConfigFalse()
    {
        $config = new Config(null);
    }
}
