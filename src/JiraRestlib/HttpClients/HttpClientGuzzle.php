<?php

namespace JiraRestlib\HttpClients;

use JiraRestlib\HttpClients\HttpClientException;
use JiraRestlib\HttpClients\HttpClientAbstract;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\AdapterException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Post\PostFile;

class HttpClientGuzzle extends HttpClientAbstract
{
    /**
     * The Guzzle client
     * 
     * @var \GuzzleHttp\Client 
     */
    protected $guzzleClient;

    /**
     * Constuctor
     * 
     * @param array $defaultOptions
     */
    public function __construct(array $defaultOptions = array())
    {
        $this->guzzleClient = new Client(array("defaults" => $defaultOptions));
    }      

    /**
     * Sends a request to the server
     *
     * @param string $url The endpoint to send the request to
     * @param string $method The request method
     * @param array  $options The key value pairs to be sent in the body
     *
     * @return string Raw response from the server in JSON Format
     *
     * @throws \JiraRestlib\HttpClients\HttpClientException
     */
    public function send($url, $method = 'GET', $options = array())
    {
        if(array_key_exists("files", $options))
        {
            $files = $options['files'];
            unset($options['files']);
        }
        
        //the options will be automatically merged with defaultOptions
        $request = $this->guzzleClient->createRequest($method, $url, $options);
        
        if(!empty($files))
        {
            $request->addHeader('X-Atlassian-Token', 'nocheck');
            $postBody = $request->getBody();

            foreach($files as $file)
            {
               $postBody->addFile(new PostFile('file', fopen($file, 'r')));
            }
        }

        try
        {
            $rawResponse = $this->guzzleClient->send($request);
        }
        catch(RequestException $e)
        {          
            $rawResponse = $e->getResponse();
            
            if($e->getPrevious() instanceof AdapterException || is_null($rawResponse))
            {
                throw new HttpClientException($e->getMessage(), $e->getCode());
            }                
        }

        $this->rawResponse = $rawResponse;
        
        return $this->getResponseJsonBody();
    }
    
     /**
     * The headers returned in the response
     *
     * @return array
     */
    public function getResponseHeaders()
    {
        return $this->rawResponse->getHeaders();
    }

    /**
     * The HTTP status response code
     *
     * @return int
     */
    public function getResponseHttpStatusCode()
    {
        return $this->rawResponse->getStatusCode();
    }
    
    /**
     * The body returned in the response
     *
     * @return mixed
     */
    public function getResponseBody()
    {
        return $this->rawResponse->getBody();
    }
    
     /**
     * The body returned in the response in JSON format
     * 
     * @return string 
     */
    public function getResponseJsonBody()
    {
        return (string)$this->getResponseBody();
    }    
}
