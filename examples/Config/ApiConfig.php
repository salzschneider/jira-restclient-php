<?php
require __DIR__.'/../../vendor/autoload.php';
require __DIR__.'/../Init/init.php';

use JiraRestlib\Api\Api;
use JiraRestlib\Config\Config;

$defaultOption = array("query"  => array('testing' => '123'),
                       "auth"   => array(USERNAME, PASSWORD),
                       "verify" => SSL_VERIFICATION);

$config = new Config(JIRA_HOST);
$config->addRequestConfigArray($defaultOption)
       ->addCommonConfig('kiskutya', 'valami')
       ->addCommonConfig(Config::HTTPCLIENT, 'guzzle');

$api = new Api($config);

$conf = $api->getConfig();
print_r($conf->getAllConfig()); 
echo PHP_EOL;

$conf->addCommonConfig('baz', 'foo');
$api->setConfig($conf);
$httpclient = $api->getHttpClient();

echo get_class($httpclient);
echo PHP_EOL;
echo "END";
echo PHP_EOL;