<?php

namespace JiraRestlib\HttpClients;

interface HttpClientInterface
{
     /**
     * The headers returned in the response
     *
     * @return array
     */
    public function getResponseHeaders();

    /**
     * The HTTP status response code
     *
     * @return int
     */
    public function getResponseHttpStatusCode();
    
    /**
     * The body returned in the response
     *
     * @return mixed
     */
    public function getResponseBody();
    
     /**
     * The body returned in the response in JSON format
     * 
     * @return string 
     */
    public function getResponseJsonBody();
    
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
    public function send($url, $method = 'GET', $options = array());
}
