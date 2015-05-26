<?php

namespace JiraRestlib\HttpClients\Client;

/**
 * @package JiraRestlib
 */
class CurlClient
{
    /**
     * Curl client instance
     * 
     * @var resource 
     */
    protected $curl = null;

    /**
     * Make a new curl reference instance
     * 
     * @return void
     */
    public function init()
    {
        if ($this->curl === null)
        {
            $this->curl = curl_init();
        }
    }

    /**
     * Set a curl option
     *
     * @param $key Curl option key
     * @param $value Curl value to be set on option
     * 
     * @return void
     */
    public function setOpt($key, $value)
    {
        curl_setopt($this->curl, $key, $value);
    }

    /**
     * Set an array of options to a curl resource
     *
     * @param array $options
     * 
     * @return void
     */
    public function setOptArray(array $options)
    {
        curl_setopt_array($this->curl, $options);
    }

    /**
     * Send a curl request
     *
     * @return mixed
     */
    public function exec()
    {
        return curl_exec($this->curl);
    }

    /**
     * Return the curl error number
     *
     * @return int
     */
    public function getErrorNumber()
    {
        return curl_errno($this->curl);
    }

    /**
     * Return the curl error message
     *
     * @return string
     */
    public function getError()
    {
        return curl_error($this->curl);
    }

    /**
     * Get info from a curl reference
     *
     * @param $type
     *
     * @return mixed
     */
    public function getInfo($type)
    {
        return curl_getinfo($this->curl, $type);
    }

    /**
     * Get the currently installed curl version
     *
     * @return array
     */
    public function getVersion()
    {
        return curl_version();
    }

    /**
     * Close the resource connection to curl
     */
    public function close()
    {
        curl_close($this->curl);

        // closed handle has to be initialized again
        $this->curl = null;
    }
}
