<?php
/**
 * This is an example of connecting with Atlassian Jira's OAuth service, thus allowing you to use their REST api.
 * 
 * Original Version
 * @link https://bitbucket.org/atlassian_tutorial/atlassian-oauth-examples/src/d625161454d1ca97b4515c6147b093fac9a68f7e/php/?at=default
 */
namespace JiraRestlib\Auth;

use GuzzleHttp\Client;
use Guzzle\Plugin\Oauth\OauthPlugin;
use JiraRestlib\Auth\AuthException;

class OAuth
{
    protected $baseUrl;
    protected $sandbox;
    protected $consumerKey;
    protected $consumerSecret;
    protected $callbackUrl;
    protected $requestTokenUrl = 'oauth';
    protected $accessTokenUrl = 'oauth';
    protected $authorizationUrl = 'OAuth.action?oauth_token=%s';
    protected $tokens;
    protected $client;
    protected $oauthPlugin;
    protected $isVerifySSL = true;

    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    public function requestTempCredentials()
    {
        return $this->requestCredentials(
                        $this->requestTokenUrl . '?oauth_callback=' . $this->callbackUrl
        );
    }

    public function requestAuthCredentials($token, $tokenSecret, $verifier)
    {
        return $this->requestCredentials(
                        $this->accessTokenUrl . '?oauth_callback=' . $this->callbackUrl . '&oauth_verifier=' . $verifier, $token, $tokenSecret
        );
    }

    protected function requestCredentials($url, $token = false, $tokenSecret = false)
    {
        $client = $this->getClient($token, $tokenSecret);

        if($this->isVerifySSL)
        {
            $response = $client->post($url)->send();
        }
        else
        {
            $response = $client->post($url, null, null, array('verify' => false))->send();
        }
 
        return $this->makeTokens($response);
    }

    protected function makeTokens($response)
    {
        $body = (string) $response->getBody();

        $tokens = array();
        parse_str($body, $tokens);

        if (empty($tokens))
        {
            throw new AuthException("An error occurred while requesting oauth token credentials");
        }

        $this->tokens = $tokens;
        return $this->tokens;
    }

    public function getClient($token = false, $tokenSecret = false)
    {
        if (!is_null($this->client))
        {
            return $this->client;
        }
        else
        {
            $this->client = new Client($this->baseUrl);

            $privateKey = $this->privateKey;
            $this->oauthPlugin = new OauthPlugin(array(
                'consumer_key' => $this->consumerKey,
                'consumer_secret' => $this->consumerSecret,
                'token' => !$token ? $this->tokens['oauth_token'] : $token,
                'token_secret' => !$token ? $this->tokens['oauth_token_secret'] : $tokenSecret,
                'signature_method' => 'RSA-SHA1',
                'signature_callback' => function($stringToSign, $key) use ($privateKey)
                {
                    if (!file_exists($privateKey))
                    {
                        throw new AuthException("Private key {$privateKey} does not exist");
                    }

                    $certificate = openssl_pkey_get_private('file://' . $privateKey);

                    $privateKeyId = openssl_get_privatekey($certificate);

                    $signature = null;

                    openssl_sign($stringToSign, $signature, $privateKeyId);
                    openssl_free_key($privateKeyId);

                    return $signature;
                }
            ));

            $this->client->addSubscriber($this->oauthPlugin);

            return $this->client;
        }
    }

    public function makeAuthUrl()
    {
        return $this->baseUrl . sprintf($this->authorizationUrl, urlencode($this->tokens['oauth_token']));
    }

    public function setConsumerKey($consumerKey)
    {
        $this->consumerKey = $consumerKey;
        return $this;
    }

    public function setConsumerSecret($consumerSecret)
    {
        $this->consumerSecret = $consumerSecret;
        return $this;
    }

    public function setCallbackUrl($callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;
        return $this;
    }

    public function setRequestTokenUrl($requestTokenUrl)
    {
        $this->requestTokenUrl = $requestTokenUrl;
        return $this;
    }

    public function setAccessTokenUrl($accessTokenUrl)
    {
        $this->accessTokenUrl = $accessTokenUrl;
        return $this;
    }

    public function setAuthorizationUrl($authorizationUrl)
    {
        $this->authorizationUrl = $authorizationUrl;
        return $this;
    }

    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;
        return $this;
    }
    
    /**
     * Set to true to enable SSL cert validation (the default), false to disable
     * 
     * @param boolean $isVerify 
     * @return \JiraRestlib\Auth\OAuth
     */
    public function setIsVerifySSL($isVerify)
    {
        $this->isVerifySSL = (boolean)$isVerify;
        return $this;
    }
}
