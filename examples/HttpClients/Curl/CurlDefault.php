<?php
require __DIR__.'/../../../vendor/autoload.php';
require __DIR__.'/../../Init/init.php';

use JiraRestlib\HttpClients\HttpClientCurl;

$defaultOption = array("query"  => array('testing' => '123'),                       
                       "verify" => SSL_VERIFICATION);

$curlHttp = new HttpClientCurl($defaultOption);
$def = $curlHttp->getDefaultOptions();

echo ('default options:').PHP_EOL;
print_r($def);

echo ('change default options:').PHP_EOL;
$curlHttp->setDefaultOptions('query', array('testing2' => 'aaa'));
$newDef = $curlHttp->getDefaultOptions();
print_r($newDef);

$url = JIRA_HOST.'/rest/api/2/serverInfo';

$resultJson = $curlHttp->send($url, 'GET');

echo ('response:').PHP_EOL;
print_r($resultJson);

echo PHP_EOL;
echo ('response headers:').PHP_EOL;
print_r($curlHttp->getResponseHeaders());

echo PHP_EOL;
echo ('response http status code:');
print_r($curlHttp->getResponseHttpStatusCode());

echo PHP_EOL;
echo "END";
