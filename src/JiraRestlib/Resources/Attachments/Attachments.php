<?php
namespace JiraRestlib\Resources\Attachments;
use JiraRestlib\Resources\ResourcesAbstract;
use JiraRestlib\Resources\ResourcesException;

class Attachments extends ResourcesAbstract
{    
    /**
     * Add one or more attachments to an issue.
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
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
    
    /**
     * Remove an attachment from an issue.
     * 
     * @param array $attachmentId id of the attachment that we will delete (10760)
     * 
     * @return void
     * https://docs.atlassian.com/jira/REST/latest/#d2e3550
     */
    public function deleteAttachment($attachmentId)
    {        
        $this->uri = "/rest/api/".$this->getApiVersion()."/attachment/".$attachmentId;        
        $this->method = "DELETE";
    }
    
    /**
     * Returns the meta-data for an attachment, including the URI of the actual attached file.
     * 
     * @param array $attachmentId id of the attachment (10760)
     * 
     * @return void
     * https://docs.atlassian.com/jira/REST/latest/#d2e3550
     */
    public function getAttachmentMetaById($attachmentId)
    {        
        $this->uri = "/rest/api/".$this->getApiVersion()."/attachment/".$attachmentId;        
        $this->method = "GET";
    }
      
    /**
     * Returns the meta information for an attachments, specifically if they are enabled and the maximum upload size allowed.
     * 
     * @return void
     * https://docs.atlassian.com/jira/REST/latest/#d2e3550
     */
    public function getAttachmentMeta()
    {        
        $this->uri = "/rest/api/".$this->getApiVersion()."/attachment/meta";        
        $this->method = "GET";
    }
    
    /**
     * Tries to expand an attachment. Output is raw and should be backwards-compatible through the course of time.
     * 
     * @param array $attachmentId id of the attachment (10760)
     * 
     * @return void
     * https://docs.atlassian.com/jira/REST/latest/#d2e3550
     */
    public function getAttachmentExpandRaw($attachmentId)
    {        
        $this->uri = "/rest/api/".$this->getApiVersion()."/attachment/".$attachmentId."/expand/raw";        
        $this->method = "GET";
    }
    
    /**
     * Tries to expand an attachment. Output is human-readable and subject to change.
     * 
     * @param array $attachmentId id of the attachment (10760)
     * 
     * @return void
     * https://docs.atlassian.com/jira/REST/latest/#d2e3550
     */
    public function getAttachmentExpandHuman($attachmentId)
    {        
        $this->uri = "/rest/api/".$this->getApiVersion()."/attachment/".$attachmentId."/expand/human";        
        $this->method = "GET";
    }
}