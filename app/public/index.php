<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
*                                                                              *
* Collide MVC Framework                                                        *
*                                                                              *
* MVC framework for PHP.                                                       *
*                                                                              *
* @package     Collide MVC Core                                                *
* @author      Collide Applications Development Team                           *
* @copyright   Copyright (c) 2011, Collide Applications                        *
* @license     http://mvc.collide-applications.com/license.txt                 *
* @link        http://mvc.collide-applications.com                             *
* @since       Version 1.0                                                     *
*                                                                              *
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

namespace collide\app\public_folder;

// start output buffering to allow printing before view loading
ob_start();

/**
 * Set error reporting level.
 * Possible values: dev, prod.
 * Dev environment will show all errors and warnings.
 * Prod environment will not show errors but will log them instead.
 */
define( 'ENVIRONMENT', 'dev' );

/**
 * Define directory separator (e.g: '/' for *NIX and '\' for Windows).
 * !!! OBS: all path constants have trailing slash.
 */
define( 'DS', DIRECTORY_SEPARATOR );

// define root folder (go up two levels to root)
define( 'ROOT_PATH', dirname( dirname( dirname( __FILE__ ) ) ) . DS );

/**
 * Define core folder.
 * We recommend to put the core folder outside of the web server root to
 * avoid unauthorized access to core files.
 * This could be done by calling PHP <code>dirname</code> function on
 * <code>ROOT_PATH</code> when creating <code>CORE_PATH</code> constant
 * e.g:
 * <code>define( 'CORE_PATH', dirname( ROOT_PATH ) . DS . 'core' . DS );</code>
 *
 * or by specifying the full path to the core folder
 * e.g:
 * <code>define( 'CORE_PATH', '/var/www/core' );</code>
 */
define( 'CORE_PATH', ROOT_PATH . 'core' . DS );

// php scripts extension (the same as this file extension)
define( 'EXT', '.' . pathinfo( __FILE__, PATHINFO_EXTENSION ) );

// get requested url
define( 'URL', rtrim( $_GET['url'], '/' ) );

// include core config
require_once( CORE_PATH . 'config' . DS . 'config' . EXT );

// include shared library
require_once( CORE_BOOTSTRAP_PATH . 'init' . EXT );
