<?php 
return array (
  'debug' => false,
  'license' => NULL,
  'salt' => 'ceaac1bc3c16d211c326466cb00ac48f',
  'url' => 'https://circlebilling.local/',
  'admin_area_prefix' => '/bb-admin',
  'sef_urls' => false,
  'timezone' => 'UTC',
  'locale' => 'en_US',
  'locale_date_format' => '%A, %d %B %G',
  'locale_time_format' => ' %T',
  'path_data' => '/var/www/circlebilling.local/src/bb-data',
  'path_logs' => '/var/www/circlebilling.local/src/bb-data/log/application.log',
  'log_to_db' => true,
  'db' => 
  array (
    'type' => 'mysql',
    'host' => 'localhost',
    'name' => 'circlebilling',
    'user' => 'vagrant',
    'password' => '',
  ),
  'twig' => 
  array (
    'debug' => true,
    'auto_reload' => true,
    'cache' => '/var/www/circlebilling.local/src/bb-data/cache',
  ),
  'api' => 
  array (
    'require_referrer_header' => false,
    'allowed_ips' => 
    array (
    ),
    'rate_span' => 3600,
    'rate_limit' => 1000,
  ),
);