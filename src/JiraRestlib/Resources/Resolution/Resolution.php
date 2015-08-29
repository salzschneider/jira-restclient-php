<?php
namespace JiraRestlib\Resources\Resolution;
use JiraRestlib\Resources\ResourcesAbstract;

class Resolution extends ResourcesAbstract
{
    /**
     * Returns a list of all resolutions.
     * 
     * @return void
     */
    public function getResolutions()
    {          
        $this->uri = "/rest/api/".$this->getApiVersion()."/resolution/";    
        $this->method = "GET";
    }     
    
    /**
     * Returns a resolution.
     * 
     * @param integer $resolutionId The resolution id
     * @return void
     */
    public function getResolutionById($resolutionId)
    {                  
        $this->uri = "/rest/api/".$this->getApiVersion()."/resolution/".$resolutionId;    
        $this->method = "GET";
    }
}