<?php
require __DIR__.'/../../../vendor/autoload.php';
require __DIR__.'/../../Init/init.php';

use JiraRestlib\HttpClients\HttpClientGuzzle;

/*$defaultOption = array("query"  => array('testing' => '123'),
                       "auth"   => array('username' => 'password'),
                       "verify" => false);*/

$defaultOption = array("query"  => array('testing' => '123'),                       
                       "verify" => SSL_VERIFICATION);

$guzzleHttp = new HttpClientGuzzle($defaultOption);
$def = $guzzleHttp->getDefaultOptions();

echo ('default options:').PHP_EOL;
print_r($def);

echo ('change default options:').PHP_EOL;
$guzzleHttp->setDefaultOptions('query', array('testing2' => 'aaa'));
$newDef = $guzzleHttp->getDefaultOptions();
print_r($newDef);

$url = JIRA_HOST.'/rest/api/2/serverInfo';

$result = $guzzleHttp->send($url, 'GET');
//convert to string (__toString() magic method)
$resultJson = (string)$result;

echo ('response:').PHP_EOL;
print_r($resultJson);

echo PHP_EOL;
echo ('response headers:').PHP_EOL;
print_r($guzzleHttp->getResponseHeaders());

echo PHP_EOL;
echo ('response http status code:');
print_r($guzzleHttp->getResponseHttpStatusCode());

echo PHP_EOL;
echo "END";
