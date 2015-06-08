<?php

use Mockery as m;
use JiraRestlib\Config\Config;
use JiraRestlib\Tests\UnitBaseTest;

class ConfigTest extends UnitBaseTest
{
    protected static $config = null;
    protected static $baseHttpClient = "guzzle";
    
    protected function getNewConfig()
    {
        return new Config("http://jira.com", self::$baseHttpClient);
    }
    
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        
        //creating a valid common config object
        self::$config = new Config("http://jira.com", self::$baseHttpClient);
    }

    public function tearDown()
    {
        m::close();
    }   
    
    public function validConstructor()
    {
        $validHttpClientTypes = Config::getValidHttpClientTypes();
        
        return array(
          array("http://jira.com", $validHttpClientTypes[0]),
          array("http://jira.com", $validHttpClientTypes[1]),
        );
    }
    
    public function invalidConstructor()
    {
        return array(
          array(null, null),
          array(1, 12),
          array("http://jira.com", "WRONG"),  
          array(null, "guzzle"),    
        );
    }
    
    public function validAddRequest()
    {
        return array(
          array("key1", "value1"),
          array("key2", "value2"),
        );
    }
    
    public function validAddRequestArray()
    {
        return array(
          array(array("key1" => "value1",
                      "key2" => "value2")),
          array(array("key3" => "value3",
                      "key4" => "value4")),
        );
    }
    
    public function validAddCommon()
    {
        return array(
          array(Config::HTTPCLIENT, "curl"),
          array(Config::JIRA_HOST, "http://new.jira.com"),  
          array("key1", "value1"),
          array("key2", "value2"),
        );
    }
    
    public function invalidAddCommon()
    {
        return array(
          array(Config::HTTPCLIENT, "WRONG"),
          array(Config::JIRA_HOST, null),  
        );
    }
    
    public function validAddCommonArray()
    {
        return array(
          array(array(Config::HTTPCLIENT => "curl",
                      Config::JIRA_HOST  => "http://new.jira.com")),  
          array(array("key1" => "value1",
                      "key2" => "value2")),
        );
    }
    
    public function invalidAddCommonArray()
    {
        return array(
          array(array(Config::HTTPCLIENT => "WRONG",
                      Config::JIRA_HOST  => "http://new.jira.com")),  
          array(array("key1"             => "value1",
                      Config::JIRA_HOST  => null)),
        );
    }
    
    /**
     * @dataProvider validConstructor
     */
    public function testCreateConfigTrue($url, $httpClientType)
    {
        $config = new Config($url, $httpClientType);        
        $this->assertInstanceOf("JiraRestlib\Config\Config", $config);
    }
    
    /**
     * @dataProvider invalidConstructor
     * @expectedException \JiraRestlib\Config\ConfigException
     */
    public function testCreateConfigFalse()
    {
        $config = new Config(null);
    }
    
    /**
     * @dataProvider validAddCommon
     */
    public function testAddCommonConfigTrue($index, $value)
    {
        $config = $this->getNewConfig();     
        $result = $config->addCommonConfig($index, $value);
        $this->assertInstanceOf("JiraRestlib\Config\Config", $result);
    }
    
    /**
     * @dataProvider invalidAddCommon
     * @expectedException \JiraRestlib\Config\ConfigException
     */
    public function testAddCommonConfigFalse($index, $value)
    {
        $config = $this->getNewConfig();     
        $result = $config->addCommonConfig($index, $value);       
    }
    
    /**
     * @dataProvider validAddCommonArray
     */
    public function testAddCommonConfigArrayTrue($configArray)
    {
        $config = $this->getNewConfig();     
        $result = $config->addCommonConfigArray($configArray);
        $this->assertInstanceOf("JiraRestlib\Config\Config", $result);
    } 
    
    /**
     * @dataProvider invalidAddCommonArray
     * @expectedException \JiraRestlib\Config\ConfigException
     */
    public function testAddCommonConfigArrayFalse($configArray)
    {
        $config = $this->getNewConfig();  
        $result = $config->addCommonConfigArray($configArray);        
    } 
    
    /**
     * @dataProvider validAddRequest
     */
    public function testAddRequestConfigTrue($index, $value)
    {
        $config = $this->getNewConfig(); 
        $result = $config->addRequestConfig($index, $value);
        $this->assertInstanceOf("JiraRestlib\Config\Config", $result);
    }
    
    /**
     * @dataProvider validAddRequestArray
     */
    public function testAddRequestConfigArrayTrue($configArray)
    {
        $config = $this->getNewConfig();  
        $result = $config->addRequestConfigArray($configArray);
        $this->assertInstanceOf("JiraRestlib\Config\Config", $result);
    }
    
    public function testGetAllConfigTrue()
    {
        $config = $this->getNewConfig();    
        $result = $config->getAllConfig();
   
        $this->assertSame($result[Config::COMMON][Config::HTTPCLIENT], self::$baseHttpClient);
    }
    
    public function testGetCommonConfigTrue()
    {
        $config = $this->getNewConfig();    
        $result = $config->getCommonConfig();
   
        $this->assertSame($result[Config::HTTPCLIENT], self::$baseHttpClient);
    }
    
    public function testGetCommonConfigByIndexTrue()
    {
        $config = $this->getNewConfig();    
        $result = $config->getCommonConfigByIndex(Config::HTTPCLIENT);
   
        $this->assertSame($result, self::$baseHttpClient);
    }
    
    /**     
     * @expectedException \JiraRestlib\Config\ConfigException
     */
    public function testGetCommonConfigByIndexFalse()
    {
        $config = $this->getNewConfig();    
        $result = $config->getCommonConfigByIndex("WRONG");
    }
    
    public function testGetRequestConfigTrue()
    {
        $config = $this->getNewConfig();    
        $config->addRequestConfig("testKey", "testValue");
        
        $result = $config->getRequestConfig();
   
        $this->assertSame($result["testKey"], "testValue");
    }
    
    public function testGetRequestConfigByIndexTrue()
    {
        $config = $this->getNewConfig();    
        $config->addRequestConfig("testKey", "testValue");
        
        $result = $config->getRequestConfigByIndex("testKey");
   
        $this->assertSame($result, "testValue");
    }
    
    /**     
     * @expectedException \JiraRestlib\Config\ConfigException
     */
    public function testGetRequestConfigByIndexFalse()
    {
        $config = $this->getNewConfig();    
        $result = $config->getRequestConfigByIndex("WRONG");
    }

    public function testGetValidHttpClientTypesTrue()
    {
        $valid = Config::getValidHttpClientTypes();
        
        $this->assertContains("guzzle", $valid);
    }
    
    public function testHasCommonIndexTrue()
    {
         $config = $this->getNewConfig();    
         $result = $config->hasCommonIndex(Config::HTTPCLIENT);
         
         $this->assertTrue($result);
    }
    
    public function testHasCommonIndexFalse()
    {
         $config = $this->getNewConfig();    
         $result = $config->hasCommonIndex("WRONG");
         
         $this->assertFalse($result);
    }
    
    public function testHasRequestIndexTrue()
    {
         $config = $this->getNewConfig();   
         $config->addRequestConfig("testKey", "testValue");
         
         $result = $config->hasRequestIndex("testKey");
         
         $this->assertTrue($result);
    }
    
    public function testHasRequestIndexFalse()
    {
         $config = $this->getNewConfig();    
         $result = $config->hasRequestIndex("WRONG");
         
         $this->assertFalse($result);
    }

}
