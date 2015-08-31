<?php
namespace JiraRestlib\Resources\User;
use JiraRestlib\Resources\ResourcesAbstract;
use JiraRestlib\Resources\ResourcesException;

class User extends ResourcesAbstract
{
    /**
     * Returns a user. This resource cannot be accessed anonymously.
     * You cannot specify both the username and the key parameters.
     * 
     * @param string $username The username
     * @param string $key The user key
     * @return void
     * @throws ResourcesException
     */
    public function getUser($username, $key = null)
    {        
        if(!empty($username) && !empty($key))
        {
            throw new ResourcesException("You cannot specify both the username and the key parameters.");
        }
        
        $parameters = array();
        
        if(empty($username))
        {
            $parameters["key"] = array($key);
        }
        else
        {
            $parameters["username"] = array($username);
        }
          
        $expandQuery = $this->getQueryUri($parameters);      
        $this->uri = "/rest/api/".$this->getApiVersion()."/user".$expandQuery;
    
        $this->method = "GET";
    }   
    
     /**
     * Create user. By default created user will not be notified with email. 
     * If password field is not set then password will be randomly generated.
      * 
     * @param array $userData New user info
     * @return void
     */
    public function createUser(array $userData)
    {        
        $this->setOptions(array("json" => $userData)); 
        $this->uri = "/rest/api/".$this->getApiVersion()."/user";    
        $this->method = "POST";
    }
    
    /**
     * Modify user. The "value" fields present will override the existing value. Fields skipped in request will not be changed.
     * You cannot specify both the username and the key parameters.
     * 
     * @param array $newData Modified fields
     * @param string $username The username
     * @param string $key The user key
     * @return void
     * @throws ResourcesException
     */
    public function editUser(array $newData, $username, $key = null)
    {        
        if(!empty($username) && !empty($key))
        {
            throw new ResourcesException("You cannot specify both the username and the key parameters.");
        }
        
        $parameters = array();
        
        if(empty($username))
        {
            $parameters["key"] = array($key);
        }
        else
        {
            $parameters["username"] = array($username);
        }
        
        $expandQuery = $this->getQueryUri($parameters);  
        $this->setOptions(array("json" => $newData));      
        $this->uri = "/rest/api/".$this->getApiVersion()."/user".$expandQuery;    
        $this->method = "PUT";
    } 
    
    /**
     * Removes user.
     * You cannot specify both the username and the key parameters.
     * 
     * @param string $username The username
     * @param string $key The user key
     * @return void
     * @throws ResourcesException
     */
    public function deleteUser($username, $key = null)
    {        
        if(!empty($username) && !empty($key))
        {
            throw new ResourcesException("You cannot specify both the username and the key parameters.");
        }
        
        $parameters = array();
        
        if(empty($username))
        {
            $parameters["key"] = array($key);
        }
        else
        {
            $parameters["username"] = array($username);
        }
          
        $expandQuery = $this->getQueryUri($parameters);      
        $this->uri = "/rest/api/".$this->getApiVersion()."/user".$expandQuery;
    
        $this->method = "DELETE";
    } 
}