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

//use an appropriate issue name or id
$issueKey = "JIR-5";

/**
 * use appropriate values
 * request - id-s have to be string
 */

//with field way
/*$updateFieldValues = array("fields" => array("summary"     => "Edited summary",
                                             "description" => "New Edited Description"));*/

//with update way
$updateFieldValues = array("update" => array("summary" => array(array("set" => "Edited summary")),
                                             "labels"  => array(array("add" => "tag1"),
                                                                array("remove" => "tag1"),
                                                                array("add" => "tag2"))));

$issueResource->editIssue($issueKey, $updateFieldValues);

$result = $api->getRequestResult($issueResource);

$resultObject = $result->getResponse();
$resultHeader = $result->getResponseHeaders();
$resultStatus = $result->getResponseHttpStatusCode();

print_r($resultObject);

echo PHP_EOL;
print_r("Status:".$resultStatus);

echo PHP_EOL;
print_r("Has error:".$result->hasError()); 
echo PHP_EOL;

if($result->hasError())
{
    print_r($result->getErrorMessages());
    print_r($result->getErrors());
}

echo PHP_EOL;
echo "END";
echo PHP_EOL;

echo "END";