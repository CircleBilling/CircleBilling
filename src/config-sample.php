<?php
/**
 * Configuration file example
 *
 * If you are not using the web-installer, you can rename this file
 * to "config.php" and fill in the values.
 * Import /install/structure.sql to your database
 * Import /install/content.sql to your database
 * Open browser http://www.youdomain.com/index.php?_url=/admin to create new admin account.
 * Remove /install directory
 */

return array(

    /**
     * Set BoxBilling license key. Get license key at http://www.boxbilling.com
     */
    'license'     => 'PRO-wfe1RJ6aqG1wurwlocc9lwouNeTcUtklsp9ujy33clb8c57fdV',

    'salt'        => '',

    /**
     * Full URL where BoxBilling is installed with trailing slash
     */
    'url'     => 'https://circlebilling.local/',

    'admin_area_prefix' =>  '/admin',

    /**
     * Enable or Disable the display of notices
     */
    'debug'     => false,

    /**
     * Enable or Disable search engine friendly urls.
     * Configure .htaccess file before enabling this feature
     * Set to TRUE if using nginx
     */
    'sef_urls'  => false,

    /**
     * Application timezone
     */
    'timezone'    =>  'UTC',

    /**
     * Set BoxBilling locale
     */
    'locale'    =>  'en_US',

    /**
     * Set default date format for localized strings
     * Format information: http://php.net/manual/en/function.strftime.php
     */
    'locale_date_format'    =>  '%A, %d %B %G',

    /**
     * Set default time format for localized strings
     * Format information: http://php.net/manual/en/function.strftime.php
     */
    'locale_time_format'    =>  ' %T',

    /**
     * Set location to store sensitive data
     */
    'path_data'  => dirname(__FILE__) . '/data',

    'path_logs'  => dirname(__FILE__) . '/data/log/application.log',

    'log_to_db'  => true,

    'db'    =>  array(
        /**
         * Database type. Don't change this if in doubt.
         */
        'type'   =>'mysql',

        /**
         * Database hostname. Don't change this if in doubt.
         */
        'host'   =>'127.0.0.1',

        /**
         * The name of the database for BoxBilling
         */
        'name'   =>'circlebilling',

        /**
         * Database username
         */
        'user'   =>'root',

        /**
         * Database password
         */
        'password'   =>'',
    ),

    'twig'   =>  array(
        'debug'         =>  false,
        'auto_reload'   =>  false,
        'cache'         =>  dirname(__FILE__) . '/data/cache',
    ),

    'api'   =>  array(
        // all requests made to API must have referrer request header with the same url as BoxBilling installation
        'require_referrer_header'   =>  false,

        // empty array will allow all IPs to access API
        'allowed_ips'       =>  array(),

        // Time span for limit in seconds
        'rate_span'         =>  60 * 60,

        // How many requests allowed per time span
        'rate_limit'        =>  1000,
    ),
);
