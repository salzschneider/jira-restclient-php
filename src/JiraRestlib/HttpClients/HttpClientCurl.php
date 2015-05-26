<?php
namespace JiraRestlib\HttpClients;
use JiraRestlib\HttpClients\Client\CurlClient;
use JiraRestlib\HttpClients\HttpClientException;
use JiraRestlib\HttpClients\HttpClientAbstract;

/**
 * Class HttpClientCurl
 * @package JiraRestlib
 */
class HttpClientCurl extends HttpClientAbstract
{
    /**
     * The client error message
     * 
     * @var string 
     */
    protected $curlErrorMessage = '';

    /**
     * The curl client error code
     * 
     * @var integer
     */
    protected $curlErrorCode = 0;

    /**
     * Procedural curl as object
     * 
     * @var CurlClient 
     */
    protected $curlClient;

    /**
     * Constructor
     */
    public function __construct(array $defaultOptions = array())
    {
        $this->curlClient     = new CurlClient();
        $this->defaultOptions = $defaultOptions;
    }

    /**
     * Sends a request to the server
     *
     * @param string $url The endpoint to send the request to
     * @param string $method The request method
     * @param array  $options The key value pairs to be sent in the body
     *
     * @return string Raw response from the server
     *
     * @throws \JiraRestlib\HttpClients\HttpClientException
     */
    public function send($url, $method = 'GET', $options = array())
    {
        $this->openConnection($url, $method, $options);
        $this->tryToSendRequest();
        
        if ($this->curlErrorCode)
        {
            throw new HttpClientException($this->curlErrorMessage, $this->curlErrorCode);
        }
        
        $this->closeConnection();
        
        return $this->rawResponse;
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
    public function openConnection($url, $method = 'GET', array $options = array())
    {
        $curlOptions = array(
            CURLOPT_URL => $url,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_RETURNTRANSFER => true, 
            CURLOPT_HEADER         => false, 
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false
        );
        
        if ($method !== 'GET')
        {
            $curlOptions[CURLOPT_POSTFIELDS] = !$this->paramsHaveFile($options) ? http_build_query($options, null, '&') : $options;
        }
        if ($method === 'DELETE' || $method === 'PUT')
        {
            $curlOptions[CURLOPT_CUSTOMREQUEST] = $method;
        }

        $this->curlClient->init();
        $this->curlClient->setOptArray($curlOptions);
    }

    /**
     * Closes an existing curl connection
     */
    public function closeConnection()
    {
        $this->curlClient->close();
    }

    /**
     * Try to send the request
     * 
     * @return void
     */
    public function tryToSendRequest()
    {
        $this->sendRequest();
        $this->curlErrorMessage       = $this->curlClient->getErrorNumber();
        $this->curlErrorCode          = $this->curlClient->getErrorNumber();
        $this->responseHttpStatusCode = $this->curlClient->getInfo(CURLINFO_HTTP_CODE);
    }

    /**
     * Send the request and get the raw response from curl
     * 
     * @return void
     */
    public function sendRequest()
    {
        $this->rawResponse = $this->curlClient->exec();
    }

    /**
     * Detect if the params have a file to upload.
     *
     * @param array $params     
     * @return boolean
     */
    private function paramsHaveFile(array $params)
    {
        foreach ($params as $value)
        {
            if ($value instanceof \CURLFile)
            {
                return true;
            }
        }
        return false;
    }
    
    /**
     * The body returned in the response in JSON format
     * 
     * @return string 
     */
    public function getResponseJsonBody()
    {
        return $this->rawResponse;
    }
}
