<?php
namespace JiraRestlib\Resources\Issue;
use JiraRestlib\Resources\ResourcesAbstract;

class IssueComment extends ResourcesAbstract
{   
    /**
     * Returns all comments for an issue.
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * @param boolean $rendered provides body rendered in HTML
     * 
     * @return void
     */
    public function getAllCommentsIssue($idOrKey, $rendered = false)
    {      
        $parameters = array();
        
        if($rendered)
        {
            $parameters["expand"] = array("renderedBody");
        }
        
        $expandQuery = $this->getQueryUri($parameters);
   
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/comment".$expandQuery;          
        $this->method = "GET";
    }
    
    /**
     * Adds a new comment to an issue.
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * @param array $comment Comment array
     * @param boolean $rendered provides body rendered in HTML
     * 
     * @return void
     */
    public function addCommentIssue($idOrKey, array $comment, $rendered = false)
    {      
        $parameters = array();
        
        if($rendered)
        {
            $parameters["expand"] = array("renderedBody");
        }
        
        $expandQuery = $this->getQueryUri($parameters);
        
        $this->setOptions(array("json" => $comment));
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/comment".$expandQuery;          
        $this->method = "POST";
    }
    
    /**
     * Returns a specific comment for an issue.
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * @param integer $commentId Id of a comment (11500)
     * @param boolean $rendered provides body rendered in HTML
     * 
     * @return void
     */
    public function getCommentByIdIssue($idOrKey, $commentId, $rendered = false)
    {      
        $parameters = array();
        
        if($rendered)
        {
            $parameters["expand"] = array("renderedBody");
        }
        
        $expandQuery = $this->getQueryUri($parameters);
   
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/comment/".$commentId.$expandQuery;          
        $this->method = "GET";
    }
    
    /**
     * Update a specific comment for an issue.
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * @param integer $commentId Id of a comment (11500)
     * @param array $comment Comment array
     * @param boolean $rendered provides body rendered in HTML
     * 
     * @return void
     */
    public function updateCommentByIdIssue($idOrKey, $commentId, $comment, $rendered = false)
    {      
        $parameters = array();
        
        if($rendered)
        {
            $parameters["expand"] = array("renderedBody");
        }
        
        $expandQuery = $this->getQueryUri($parameters);
   
        $this->setOptions(array("json" => $comment));
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/comment/".$commentId.$expandQuery;          
        $this->method = "PUT";
    }
    
    /**
     * Delete a specific comment for an issue.
     * 
     * @param string $idOrKey the issue id or key (i.e. JRA-1330)
     * @param integer $commentId Id of a comment (11500)
     * 
     * @return void
     */
    public function deleteCommentByIdIssue($idOrKey, $commentId)
    {      
        $this->uri = "/rest/api/".$this->getApiVersion()."/issue/".$idOrKey."/comment/".$commentId;        
        $this->method = "DELETE";
    }    
}