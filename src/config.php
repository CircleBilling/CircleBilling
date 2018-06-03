<?php 
return array (
  'debug' => false,
  'license' => NULL,
  'salt' => '32bb22886b67480756939a04167dc5d7',
  'url' => 'https://circlebilling.local/',
  'admin_area_prefix' => '/admin',
  'sef_urls' => false,
  'timezone' => 'UTC',
  'locale' => 'en_US',
  'locale_date_format' => '%A, %d %B %G',
  'locale_time_format' => ' %T',
  'path_data' => '/var/www/circlebilling.local/src/data',
  'path_logs' => '/var/www/circlebilling.local/src/data/log/application.log',
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
    'cache' => '/var/www/circlebilling.local/src/data/cache',
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