#Jira-RestClient-PHP (Alpha version)

[![Build Status](https://travis-ci.org/salzschneider/jira-restclient-php.svg?branch=master)](https://travis-ci.org/salzschneider/jira-restclient-php)

It will be an Atlassian JIRA Rest API client library for PHP. I hope you will be able to use your Jira API easier with this. 
It's an alpha/prototype version, **please don't use it in production environment**. 

Installing
----------------
- download the project
- run: `composer update`

Examples
----------------
You can find some example script to check how it works. Every resource works on the same way. 
- create a copy from initSample.php in examples/Init and rename it to init.php
- use your JIRA settings in the init.php
- execute example PHP scripts, you can find them in the examples/Resources folder

Missing features
----------------
- all JIRA API resources - issue, project, comment etc. - [JIRA Rest API Doc.](https://docs.atlassian.com/jira/REST/latest/)
- httpClientCurl doesn't contain all Guzzle feature - it will be a backup solution if you have [Guzzle](https://github.com/guzzle/guzzle) conflict or you don't wanna use that package
- documentation
- oAuth2 

Requirements
----------------
- PHP 5 >= 5.4.0
- cURL - it will be optional
- [Guzzle](https://github.com/guzzle/guzzle) - v5
