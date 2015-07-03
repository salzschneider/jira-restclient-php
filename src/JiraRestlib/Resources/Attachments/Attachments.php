<?php
namespace JiraRestlib\Resources\Attachments;
use JiraRestlib\Resources\ResourcesAbstract;
use JiraRestlib\Resources\ResourcesException;

class Attachments extends ResourcesAbstract
{    
    /**
     * Add one or more attachments to an issue.
     * 
     * @param string $idOrKey the issue id or key to update (i.e. JRA-1330)
     * @param array $files filenames with full path - multipart posts
     * 
     * @return void
     * https://docs.atlassian.com/jira/REST/latest/#d2e4287
     */
    public function addAttachment($idOrKey, $files)
    {        
        if(empty($files))
        {
            throw new ResourcesException("The attachment list with filenames is empty");
        }
        
        $this->setOptions(array("files" => $files));
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/attachments";
        $this->method = "POST";
    }
}