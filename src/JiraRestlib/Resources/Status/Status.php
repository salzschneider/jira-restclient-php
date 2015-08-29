<?php
namespace JiraRestlib\Resources\Status;
use JiraRestlib\Resources\ResourcesAbstract;

class Status extends ResourcesAbstract
{
    /**
     * Returns a list of all statuses.
     * 
     * @return void
     */
    public function getStatuses()
    {          
        $this->uri = "/rest/api/".$this->getApiVersion()."/status/";    
        $this->method = "GET";
    }     
    
    /**
     * Returns a full representation of the Status having the given id or name.
     * 
     * @param string|integer $statusId A numeric Status id or a status name
     * @return void
     */
    public function getStatusById($statusId)
    {                  
        $this->uri = "/rest/api/".$this->getApiVersion()."/status/".$statusId;    
        $this->method = "GET";
    }
    
    /**
     * Returns a list of all status categories.
     * 
     * @return void
     */
    public function getStatusCategories()
    {          
        $this->uri = "/rest/api/".$this->getApiVersion()."/statuscategory/";    
        $this->method = "GET";
    }
    
    /**
     * Returns a full representation of the StatusCategory having the given id or key.
     * 
     * @param string|integer $categoryId A numeric StatusCategory id or a status category key
     * @return void
     */
    public function getStatusCategoryById($categoryId)
    {          
        $this->uri = "/rest/api/".$this->getApiVersion()."/statuscategory/".$categoryId;
        $this->method = "GET";
    }  
}