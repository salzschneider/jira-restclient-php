<?php
use Mockery as m;
use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Resources\Field\Field;
use JiraRestlib\Tests\IntegrationBaseTest;

class FieldTest extends IntegrationBaseTest
{
    public function invalidField()
    {
        return array(
          array(array()),
          array(array("WRONG" => "Wrong")),           
        );
    }
    
    public function tearDown()
    {
        m::close();
    }   
   
    public function testGetFieldTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $fieldResource = new Field();

        $fieldResource->getFields();
        $result = $api->getRequestResult($fieldResource);          
        $this->assertFalse($result->hasError());   
    }
    
    public function testAddCustomFieldTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $fieldResource = new Field();

        $field = array("name"         => "deleteTestField",
                       "description"  => "Custom field for picking groups",
                       "type"         => "com.atlassian.jira.plugin.system.customfieldtypes:grouppicker",
                       "searcherKey"  => "com.atlassian.jira.plugin.system.customfieldtypes:grouppickersearcher");
        
        $fieldResource->addCustomField($field);
        $result = $api->getRequestResult($fieldResource); 
       
        $this->assertFalse($result->hasError());   
    }
    
    /**
     * @dataProvider invalidField
     */
    public function testAddCustomFieldFalse($field)
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $fieldResource = new Field();
        
        $fieldResource->addCustomField($field);
        $result = $api->getRequestResult($fieldResource); 
       
        $this->assertTrue($result->hasError());   
    }
    
    public function testGetCustomFieldOptionTrue()
    {
        $config = new Config(self::$jiraRestHost);
        $config->setSSLVerification(self::$isVerified);
        $config->setJiraAuth(self::$jiraRestUsername, self::$jiraRestPassword);
        
        $api = new Api($config);
        $fieldResource = new Field();
        
        $fieldResource->getCustomFieldOption(self::$customFieldOptionId);
        $result = $api->getRequestResult($fieldResource);      
        $this->assertFalse($result->hasError());   
    }
}