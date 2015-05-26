<?php
namespace JiraRestlib\HttpClients;
use JiraRestlib\HttpClients\Source\CurlSource;
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
     * The raw response from the server
     * 
     * @var string|boolean 
     */
    protected $rawResponse;

    /**
     * Procedural curl as object
     * 
     * @var CurlSource 
     */
    protected $curlSource;

    /**
     * Constructor
     */
    public function __construct(array $defaultOptions = array())
    {
        $this->curlSource = new CurlSource();
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
     * Opens a new curl connection
     * @todo configbol a ssl verify
     *
     * @param string $url The endpoint to send the request to
     * @param string $method The request method
     * @param array  $options The key value pairs to be sent in the body
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

        $this->curlSource->init();
        $this->curlSource->setOptArray($curlOptions);
    }

    /**
     * Closes an existing curl connection
     */
    public function closeConnection()
    {
        $this->curlSource->close();
    }

    /**
     * Try to send the request
     * 
     * @return void
     */
    public function tryToSendRequest()
    {
        $this->sendRequest();
        $this->curlErrorMessage       = $this->curlSource->getErrorNumber();
        $this->curlErrorCode          = $this->curlSource->getErrorNumber();
        $this->responseHttpStatusCode = $this->curlSource->getInfo(CURLINFO_HTTP_CODE);
    }

    /**
     * Send the request and get the raw response from curl
     * 
     * @return void
     */
    public function sendRequest()
    {
        $this->rawResponse = $this->curlSource->exec();
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
