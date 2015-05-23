<?php

namespace JiraRestlib\HttpClients;

use JiraRestlib\HttpClients\HttpClientException;
use JiraRestlib\HttpClients\HttpClientAbstract;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\AdapterException;
use GuzzleHttp\Exception\RequestException;

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
        if(is_null($this->guzzleClient))
        {
            $this->defaultOptions = $defaultOptions;
            $this->guzzleClient = new Client(array("defaults" => $defaultOptions));
        }
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
        //the options will be automatically merged with defaultOptions
        $request = $this->guzzleClient->createRequest($method, $url, $options);

        try
        {
            $rawResponse = $this->guzzleClient->send($request);
        }
        catch(RequestException $e)
        {
            if($e->getPrevious() instanceof AdapterException)
            {
                throw new HttpClientException($e->getMessage(), $e->getCode());
            }

            $rawResponse = $e->getResponse();
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
