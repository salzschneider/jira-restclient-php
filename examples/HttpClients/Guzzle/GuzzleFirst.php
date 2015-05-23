<?php
require __DIR__.'/../../../vendor/autoload.php';
require __DIR__.'/../../Init/init.php';

use JiraRestlib\HttpClients\HttpClientGuzzle;

$guzzleHttp = new HttpClientGuzzle();

$url = JIRA_HOST.'/rest/api/2/serverInfo';

$result = $guzzleHttp->send($url, 'GET', array('verify' => false));

$resultJson = (string)$result;
print_r($resultJson);

echo "END";
