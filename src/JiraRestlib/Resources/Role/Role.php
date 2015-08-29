<?php
namespace JiraRestlib\Resources\Role;
use JiraRestlib\Resources\ResourcesAbstract;

class Role extends ResourcesAbstract
{
    /**
     * Get all the ProjectRoles available in JIRA. Currently this list is global.
     * 
     * @return void
     */
    public function getRoles()
    {          
        $this->uri = "/rest/api/".$this->getApiVersion()."/role/";    
        $this->method = "GET";
    }     
    
    /**
     * Returns a role.
     * 
     * @param integer $roleId The role id
     * @return void
     */
    public function getRoleById($roleId)
    {                  
        $this->uri = "/rest/api/".$this->getApiVersion()."/role/".$roleId;    
        $this->method = "GET";
    }
}