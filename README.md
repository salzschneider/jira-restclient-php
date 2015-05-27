Jira-RestClient-PHP (Alpha version)
================

It will be an Atlassian JIRA Rest API client library for PHP. You will be able to communicate with your JIRA easly using this library.
It's an alpha/prototype version, **please don't use it yet**. 

Installing
----------------
- download the project
- run: `composer update`

Examples
----------------
You can try some working test. 
- create a copy from initSample.php in examples/Init folder named init.php
- use your JIRA settings in the init.php
- try the PHP-s in the examples folder via cli

Missing features
----------------
- all JIRA API function implementations - issue, project, comment etc.
- httpClientCurl doesn't work yet - it will be a backup solution if you have [Guzzle](https://github.com/guzzle/guzzle) conflict or don't wanna use that package
- lots of unit and automated JIRA API tests (in travis)
- documentation and examples
- oAuth2 

Requirements
----------------
- PHP 5 >= 5.4.0
- cURL - it will be optional
- [Guzzle](https://github.com/guzzle/guzzle) - v5
