<?php
require __DIR__.'/../../../vendor/autoload.php';
require __DIR__.'/../../Init/init.php';

use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Resources\Issue\Issue;

$defaultOption = array("query"     => array('testing' => '123'),
                       "auth"      => array(USERNAME, PASSWORD),
                       "verify"    => SSL_VERIFICATION);

$config = new Config(JIRA_HOST);
$config->addCommonConfig(Config::HTTPCLIENT, 'curl');
$config->addRequestConfigArray($defaultOption);

$api = new Api($config);
$issueResource = new Issue();
$issueResource->getIssue('JIR-2', array("updated", "status"), array("name", "schema"));

$result = $api->getRequestResult($issueResource);

$resultObject = $result->getResponse();
$resultHeader = $result->getResponseHeaders();
$resultStatus = $result->getResponseHttpStatusCode();

print_r($resultObject);

if($result->hasError())
{
    print_r($result->getErrorMessages());
}
echo PHP_EOL;
print_r("Status:".$resultStatus);

echo PHP_EOL;
print_r("Has error:".$result->hasError()); 

echo PHP_EOL;
echo "END";
echo PHP_EOL;