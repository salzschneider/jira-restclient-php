<?php

namespace JiraRestlib\HttpClients\Source;

/**
 * @package JiraRestlib
 */
class CurlSource
{
    /**
     * @var resource Curl resource instance
     */
    protected $_curl = null;

    /**
     * Make a new curl reference instance
     */
    public function init()
    {
        if ($this->_curl === null)
        {
            $this->_curl = curl_init();
        }
    }

    /**
     * Set a curl option
     *
     * @param $key
     * @param $value
     */
    public function setOpt($key, $value)
    {
        curl_setopt($this->_curl, $key, $value);
    }

    /**
     * Set an array of options to a curl resource
     *
     * @param array $options
     */
    public function setOptArray(array $options)
    {
        curl_setopt_array($this->_curl, $options);
    }

    /**
     * Send a curl request
     *
     * @return mixed
     */
    public function exec()
    {
        return curl_exec($this->_curl);
    }

    /**
     * Return the curl error number
     *
     * @return int
     */
    public function getErrorNumber()
    {
        return curl_errno($this->_curl);
    }

    /**
     * Return the curl error message
     *
     * @return string
     */
    public function getError()
    {
        return curl_error($this->_curl);
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
        return curl_getinfo($this->_curl, $type);
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
        curl_close($this->_curl);

        // closed handle has to be initialized again
        $this->curl = null;
    }
}