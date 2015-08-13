<?php
require __DIR__.'/../../../vendor/autoload.php';
require __DIR__.'/../../Init/init.php';

use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Resources\Issue\Issue;
use JiraRestlib\Result\ResultAbstract;

/**
 * From official Atlassian JIRA API
 */
$config = new Config("https://jira.atlassian.com/");
$config->addCommonConfig(Config::RESPONSE_FORMAT, ResultAbstract::RESPONSE_FORMAT_OBJECT);
$api = new Api($config);

$issueResource = new Issue();
$issueResource->getIssue('JRA-9', array("updated"), array("names", "renderedFields"));

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