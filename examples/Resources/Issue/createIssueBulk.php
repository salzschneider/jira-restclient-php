<?php
require __DIR__.'/../../../vendor/autoload.php';
require __DIR__.'/../../Init/init.php';

use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;
use JiraRestlib\Resources\Issue\Issue;
use JiraRestlib\Result\ResultAbstract;

$defaultOption = array("auth"      => array(USERNAME, PASSWORD),
                       "verify"    => SSL_VERIFICATION);

$config = new Config(JIRA_HOST);
$config->addRequestConfigArray($defaultOption);
$config->addCommonConfig(Config::RESPONSE_FORMAT, ResultAbstract::RESPONSE_FORMAT_OBJECT);

$api = new Api($config);
$issueResource = new Issue();

//request - id-s have to be string
$issue1 = array("fields" => array("project"     => array("id" => "11100"),
                                  "summary"     => "Issue 1 Bulk",
                                  "issuetype"   => array("id" => "3"),
                                  "priority"    => array("id" => "2"),
                                  "description" => "some description here, bulk 2"));

$issue2 = array("fields" => array("project"     => array("id" => "11100"),
                                  "summary"     => "Issue 2 Bulk",
                                  "issuetype"   => array("id" => "3"),
                                  "priority"    => array("id" => "2"),
                                  "description" => "some description here, bulk 2"));

$issueList = array("issueUpdates" => array($issue1, $issue2));

$issueResource->createIssueBulk($issueList);

$result = $api->getRequestResult($issueResource);

$resultObject = $result->getResponse();
$resultHeader = $result->getResponseHeaders();
$resultStatus = $result->getResponseHttpStatusCode();

print_r($resultObject);

if($result->hasError())
{
    print_r($result->getResponse());
}
echo PHP_EOL;
print_r("Status:".$resultStatus);

echo PHP_EOL;
print_r("Has error:".$result->hasError()); 

echo PHP_EOL;
echo "END";
echo PHP_EOL;

echo "END";