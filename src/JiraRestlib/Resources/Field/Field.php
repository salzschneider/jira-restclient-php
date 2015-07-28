<?php
namespace JiraRestlib\Resources\Field;
use JiraRestlib\Resources\ResourcesAbstract;

class Field extends ResourcesAbstract
{
    /**
     * Returns a list of all fields, both System and Custom
     * 
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e644
     */
    public function getFields()
    {        
        $this->uri = "/rest/api/".$this->getApiVersion()."/field/";
        $this->method = "GET";
    }
    
    /**
     * Returns a full representation of the Custom Field Option that has the given id.
     * 
     * @param string $customFieldOptionId option id of a custom field (e.g. single choice field)
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e3
     */
    public function getCustomFieldOption($customFieldOptionId)
    {
        $this->uri = "/rest/api/".$this->getApiVersion()."/customFieldOption/".$customFieldOptionId;
        $this->method = "GET";
    }

    /**
     * Creates a custom field using a definition (object encapsulating custom field data)
     * 
     * @param array $field Representation of the custom field
     * @return void
     * @link https://docs.atlassian.com/jira/REST/latest/#d2e644
     */
    public function addCustomField(array $field)
    {        
        $this->setOptions(array("json" => $field));
        $this->uri = "/rest/api/".$this->getApiVersion()."/field/";
        $this->method = "POST";
    }
}