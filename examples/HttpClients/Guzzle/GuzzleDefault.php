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

echo ('response:').PHP_EOL;
//convert to string (__toString() magic method)
$resultJson = (string)$result;
print_r($resultJson);

echo "END";
