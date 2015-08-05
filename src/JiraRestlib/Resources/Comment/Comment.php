<?php
namespace JiraRestlib\Resources\Comment;
use JiraRestlib\Resources\ResourcesAbstract;

class Comment extends ResourcesAbstract
{           
    /**
     * Returns the keys of all properties for the comment identified by the key or by the id.
     * 
     * @param integer $commentId Id of a comment (11500)     
     * @return void
     */
    public function getCommentPropertyKeys($commentId)
    {
        $this->uri = "/rest/api/".$this->getApiVersion()."/comment/".$commentId."/properties";          
        $this->method = "GET";
    }
    
    /**
     * Sets the value of the specified comment's property.
     * 
     * @param integer $commentId Id of a comment (11500)  
     * @param string $propertyKey The key of the property to return
     * @param string $propertyValue The value connected to the key
     * @return void
     */
    public function setCommentProperty($commentId, $propertyKey, $propertyValue)
    {        
        $this->setOptions(array("json" => $propertyValue));
        $this->uri = "/rest/api/".$this->getApiVersion()."/comment/".$commentId."/properties/".$propertyKey;          
        $this->method = "PUT";
    }
    
    /**
     * Returns the value of the property with a given key from the comment identified by the key or by the id. 
     * 
     * @param integer $commentId Id of a comment (11500)  
     * @param string $propertyKey The key of the property to return
     * @return void
     */
    public function getCommentPropertyValueByKey($commentId, $propertyKey)
    {        
        $this->uri = "/rest/api/".$this->getApiVersion()."/comment/".$commentId."/properties/".$propertyKey;          
        $this->method = "GET";
    }
    
    /**
     * Removes the property from the comment identified by the key or by the id. 
     * 
     * @param integer $commentId Id of a comment (11500)  
     * @param string $propertyKey The key of the property to return
     * @return void
     */
    public function deleteCommentProperty($commentId, $propertyKey)
    {        
        $this->uri = "/rest/api/".$this->getApiVersion()."/comment/".$commentId."/properties/".$propertyKey;          
        $this->method = "DELETE";
    }
}