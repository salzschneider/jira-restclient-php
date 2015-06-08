<?php
namespace JiraRestlib\Config;

/**
 * Config container class
 * 
 */
class Config
{
    /**
     * Default httpclient type (curl, guzzle etc)
     */
    const DEFAULT_HTTPCLIENT = 'guzzle';
    
    /**
     * Index of jira base url config value
     */
    const JIRA_HOST = 'jira_base_url';
    
    /**
     * Index of httpclient type config value
     */
    const HTTPCLIENT = 'httpclient';
    
    /**
     * Response format: json, array, object
     */
    const RESPONSE_FORMAT = "response_format";
    
    /**
     * Config sets
     */
    const COMMON  = 'common';
    const REQUEST = 'request';
    
    /**
     * Config container:
     * 
     * - common: JiraRestlib specific configurations
     * - request: Guzzle request option specific configuration
     *  
     * @var array
     */
    protected $config = array(self::COMMON  => array(),
                              self::REQUEST => array());
    
    /**
     * Valid httpclient types
     * 
     * @var array 
     */        
    protected static $configValidator = array(self::HTTPCLIENT => array("guzzle", "curl"));
    
    /**     
     * Setting minimum configuration values
     * 
     * @param string $jiraBaseUrl Jira base url e.g https://myjira.com/ 
     * @param string $httpclient A valid httpclient type (guzzle or curl)
     * 
     * @return void
     */
    public function __construct($jiraBaseUrl, $httpclient = "guzzle")
    {
        $this->addCommonConfig(self::JIRA_HOST, $jiraBaseUrl);   
        $this->addCommonConfig(self::HTTPCLIENT, $httpclient);       
    }    

    /**
     * Get special config value set with parameter index in common config
     * 
     * @param string $index config name
     * @return mixed
     * 
     * @throws \JiraRestlib\Config\ConfigException
     */
    public function getCommonConfigByIndex($index)
    {
        if(array_key_exists($index, $this->config[self::COMMON]))
        {
            $returnConfig = $this->config[self::COMMON][$index];
        }
        else
        {
            throw new \JiraRestlib\Config\ConfigException("There is no config element in common config with this index: " . $index);
        }

        return $returnConfig;
    }
    
    /**
     * Get special config value set with parameter index in request config
     * 
     * @param string $index config name
     * @return mixed
     * 
     * @throws \JiraRestlib\Config\ConfigException
     */
    public function getRequestConfigByIndex($index)
    {
        $returnConfig = null;

        if(array_key_exists($index, $this->config[self::REQUEST]))
        {
            $returnConfig = $this->config[self::REQUEST][$index];
        }
        else
        {
            throw new \JiraRestlib\Config\ConfigException("There is no config element in request config with this index: " . $index);
        }

        return $returnConfig;
    }

    /**
     * Is the common config item set with this index
     * 
     * @param string $index
     * @return boolean
     */
    public function hasCommonIndex($index)
    {
        return array_key_exists($index, $this->config[self::COMMON]);
    }
    
    /**
     * Is the request config item set with this index
     * 
     * @param string $index
     * @return boolean
     */
    public function hasRequestIndex($index)
    {
        return array_key_exists($index, $this->config[self::REQUEST]);
    }

    /**
     * Get the full config container
     * 
     * @return array
     */
    public function getAllConfig()
    {
        return $this->config;
    }
    
    /**
     * Get the full common config container
     * 
     * @return array
     */
    public function getCommonConfig()
    {
         return $this->config[self::COMMON];
    }
    
    /**
     * Get the full request config container
     * 
     * @return array
     */
    public function getRequestConfig()
    {
        return $this->config[self::REQUEST];
    }
    
    /**
     * Add or replace a config value with parameter index in the common part of config
     * JiraRestlib specific configurations
     * 
     * @param string $index
     * @param mixed $value
     * @return \JiraRestlib\Config
     * 
     * @throws \JiraRestlib\Config\ConfigException
     */
    public function addCommonConfig($index, $value)
    {
        if($index == self::HTTPCLIENT && !$this->isValidHttpClient($value))
        {
            throw new \JiraRestlib\Config\ConfigException("Invalid httpclient: ".$value.". It can be: ". implode(", ", self::$configValidator[self::HTTPCLIENT]));
        }
        
        if($index == self::JIRA_HOST && !$this->isValidJiraBaseUrl($value))
        {
            throw new \JiraRestlib\Config\ConfigException("Jira base url config value is empty. Config index: ".self::JIRA_HOST);
        }
        
        $this->config[self::COMMON][$index] = $value;
        return $this;
    }
    
     /**
      * Is the parameter a valid httpclient
      * 
      * @param string $httpclient 
      * @return boolean
      */
    protected function isValidHttpClient($httpclient)
    {       
        $isValid = false;
        
        if(in_array($httpclient, self::$configValidator[self::HTTPCLIENT]))
        {           
            $isValid = true;
        }
        
        return $isValid;
    }
    
     /**
      * Is the parameter a valid url
      * 
      * @param string $url Jira base url without uri e.g https://mjira.com
      * @return boolean
      */
    protected function isValidJiraBaseUrl($url)
    {       
        $isValid = false;
        
        if(!empty($url))
        {
            $isValid = true;
        }
        
        return $isValid;
    }

    /**
     * Add or replace a bunch of key config value in the common part of config
     * JiraRestlib specific configurations
     * 
     * @param array $configArray
     * @return \JiraRestlib\Config
     */
    public function addCommonConfigArray(array $configArray)
    {
        foreach($configArray as $index => $value)
        {
            $this->addCommonConfig($index, $value);
        }

        return $this;
    }
    
    /**
     * Add or replace a config value with parameter index in the Guzzle configuration (request) part of config
     *
     * Guzzle options ($index - $value)
     * - headers: Associative array of headers to add to the request
     * - body: string|resource|array|StreamInterface request body to send
     * - json: mixed Uploads JSON encoded data using an application/json Content-Type header.
     * - query: Associative array of query string values to add to the request
     * - auth: array|string HTTP auth settings (user, pass[, type="basic"])
     * - version: The HTTP protocol version to use with the request
     * - cookies: true|false|CookieJarInterface To enable or disable cookies
     * - allow_redirects: true|false|array Controls HTTP redirects
     * - save_to: string|resource|StreamInterface Where the response is saved
     * - events: Associative array of event names to callables or arrays
     * - subscribers: Array of event subscribers to add to the request
     * - exceptions: Specifies whether or not exceptions are thrown for HTTP protocol errors
     * - timeout: Timeout of the request in seconds. Use 0 to wait indefinitely
     * - connect_timeout: Number of seconds to wait while trying to connect. (0 to wait indefinitely)
     * - verify: SSL validation. True/False or the path to a PEM file
     * - cert: Path a SSL cert or array of (path, pwd)
     * - ssl_key: Path to a private SSL key or array of (path, pwd)
     * - proxy: Specify an HTTP proxy or hash of protocols to proxies
     * - debug: Set to true or a resource to view adapter specific debug info
     * - stream: Set to true to stream a response body rather than download it all up front
     * - expect: true/false/integer Controls the "Expect: 100-Continue" header
     * - config: Associative array of request config collection options
     * - decode_content: true/false/string to control decoding content-encoding responses
     * 
     * @param string $index
     * @param mixed $value
     * 
     * @return \JiraRestlib\Config
     * 
     * Displays http://guzzle.readthedocs.org/en/latest/clients.html
     * @link http://guzzle.readthedocs.org/en/latest/clients.html
     */
    public function addRequestConfig($index, $value)
    {
        $this->config[self::REQUEST][$index] = $value;
        return $this;
    }

    /**
     * Add or replace a bunch of config value in the Guzzle configuration (request) part of config 
     *
     * @param array $configArray
     * Guzzle options ($index - $value)
     * - headers: Associative array of headers to add to the request
     * - body: string|resource|array|StreamInterface request body to send
     * - json: mixed Uploads JSON encoded data using an application/json Content-Type header.
     * - query: Associative array of query string values to add to the request
     * - auth: array|string HTTP auth settings (user, pass[, type="basic"])
     * - version: The HTTP protocol version to use with the request
     * - cookies: true|false|CookieJarInterface To enable or disable cookies
     * - allow_redirects: true|false|array Controls HTTP redirects
     * - save_to: string|resource|StreamInterface Where the response is saved
     * - events: Associative array of event names to callables or arrays
     * - subscribers: Array of event subscribers to add to the request
     * - exceptions: Specifies whether or not exceptions are thrown for HTTP protocol errors
     * - timeout: Timeout of the request in seconds. Use 0 to wait indefinitely
     * - connect_timeout: Number of seconds to wait while trying to connect. (0 to wait indefinitely)
     * - verify: SSL validation. True/False or the path to a PEM file
     * - cert: Path a SSL cert or array of (path, pwd)
     * - ssl_key: Path to a private SSL key or array of (path, pwd)
     * - proxy: Specify an HTTP proxy or hash of protocols to proxies
     * - debug: Set to true or a resource to view adapter specific debug info
     * - stream: Set to true to stream a response body rather than download it all up front
     * - expect: true/false/integer Controls the "Expect: 100-Continue" header
     * - config: Associative array of request config collection options
     * - decode_content: true/false/string to control decoding content-encoding responses
     *    
     * @return \JiraRestlib\Config
     * 
     * Displays http://guzzle.readthedocs.org/en/latest/clients.html
     * @link http://guzzle.readthedocs.org/en/latest/clients.html
     */
    public function addRequestConfigArray(array $configArray)
    {
        foreach($configArray as $index => $value)
        {
            $this->addRequestConfig($index, $value);
        }

        return $this;
    }
    
    /**
     * Get valid httpClient types e.g.: guzzle, curl
     * 
     * @return array
     */
    public static function getValidHttpClientTypes()
    {
        return self::$configValidator[self::HTTPCLIENT];
    }

}
