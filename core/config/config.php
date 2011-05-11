<?php
namespace collide\core\config;

if( !defined( 'ROOT_PATH' ) ) die( '403: Forbidden' );

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

/**
 * Define core constants used by core libraries and scripts.
 *
 * @package     Collide MVC Core
 * @subpackage  Config
 * @category    Initialization
 * @author      Collide Applications Development Team
 * @link        http://mvc.collide-applications.com/docs/
 */

/**
 * Define core paths
 */

// path to logs folder
define( 'CORE_LOG_PATH', CORE_PATH . 'logs' . DS );
// path to temp folder
define( 'CORE_TEMP_PATH', CORE_PATH . 'temp' . DS );
// path to lib folder
define( 'CORE_LIB_PATH', CORE_PATH . 'lib' . DS );
// path to internal lib folder
define( 'CORE_LIB_INT_PATH', CORE_LIB_PATH . 'internal' . DS );
// path to external lib folder
define( 'CORE_LIB_EXT_PATH', CORE_LIB_PATH . 'external' . DS );
// path to helpers folder
define( 'CORE_HELPERS_PATH', CORE_PATH . 'helpers' . DS );
// path to bootstrap folder
define( 'CORE_BOOTSTRAP_PATH', CORE_PATH . 'bootstrap' . DS );

/**
 * Define paths to external libraries
 */

// Doctrine version
define( 'DOCTRINE_VERSION', '1.2.2' );
// path to Doctrine folder
define( 'DOCTRINE_PATH', CORE_LIB_EXT_PATH . 'Doctrine-' . DOCTRINE_VERSION . DS );

/**
 * Define application paths
 */

/**
 * Path to application folder.
 *
 * !!! OBS: If you move application to another path change this value and change
 * path to public folder from .htaccess.
 */
define( 'APP_PATH', ROOT_PATH . 'app' . DS );
// path to config folder
define( 'APP_CONFIG_PATH', APP_PATH . 'config' . DS );
// path to models folder
define( 'APP_MODELS_PATH', APP_PATH . 'models' . DS );
// path to views folder
define( 'APP_VIEWS_PATH', APP_PATH . 'views' . DS );
// path to controllers folder
define( 'APP_CONTROLLERS_PATH', APP_PATH . 'controllers' . DS );
// path to libraries folder
define( 'APP_LIB_PATH', APP_PATH . 'lib' . DS );
// path to helpers folder
define( 'APP_HELPERS_PATH', APP_PATH . 'helpers' . DS );
// path to public folder
define( 'APP_PUBLIC_PATH', APP_PATH . 'public' . DS );
// path to templates folder
define( 'APP_TEMPLATES_PATH', APP_PATH . 'templates' . DS );

/**
 * Define modules paths
 */

/**
 * Path to modules folder.
 *
 * Use <code>sprintf</code> function to replace module placeholder with current
 * module name.
 */
define( 'APP_MODULES_PATH', APP_PATH . 'modules' . DS );
define( 'APP_MODULES_CONTROLLERS_PATH', APP_MODULES_PATH . '%s' . DS . 'controllers' . DS );
define( 'APP_MODULES_CONFIG_PATH', APP_MODULES_PATH . '%s' . DS . 'config' . DS );
define( 'APP_MODULES_HELPERS_PATH', APP_MODULES_PATH . '%s' . DS . 'helpers' . DS );
define( 'APP_MODULES_LIB_PATH', APP_MODULES_PATH . '%s' . DS . 'lib' . DS );
define( 'APP_MODULES_MODELS_PATH', APP_MODULES_PATH . '%s' . DS . 'models' . DS );
define( 'APP_MODULES_VIEWS_PATH', APP_MODULES_PATH . '%s' . DS . 'views' . DS );

/**
 * Define other constants
 */

// current version
define( 'VERSION', '1.0' );

// custom libraries prefix
define( 'LIB_PREFIX', '_' );