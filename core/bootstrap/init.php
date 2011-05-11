<?php
namespace collide\core\bootstrap;

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
 * Prepare framework to run and initialize requested controller.
 *
 * @package     Collide MVC Core
 * @subpackage  Bootstrap
 * @category    Initialization
 * @author      Collide Applications Development Team
 * @link        http://mvc.collide-applications.com/docs/
 */

if( !function_exists( 'setDisplayErrors' ) ){
    /**
     * Check environment type and display or log errors.
     *
     * @access  public
     * @return  void
     */
    function setDisplayErrors(){
        if( trim( strtolower( ENVIRONMENT ) ) == 'dev' ){   // development
            // display all errors
            error_reporting( E_ALL );
            ini_set( 'display_errors', 'On' );
        }else{                                              // production
            // do not display errors
            error_reporting( E_ALL );
            ini_set( 'display_errors', 'Off' );
            ini_set( 'log_errors', 'On' );
            ini_set( 'error_log', CORE_LOG_PATH . 'php.log' );
        }
    }
}

if( !function_exists( 'checkMvc' ) ){
    /**
     * Check if Collide MVC is prepared.
     * e.g:
     * - security key changed;
     * - utils controller has the default name;
     *
     * @access  public
     * @return  void
     */
    function checkMvc(){
        incLib( 'exception' );

        // check if default security key was changed
        require( APP_CONFIG_PATH . 'config.php' );
        if( isset( $cfg['security']['key'] ) && hash( 'md5', 'Collide MVC' ) == $cfg['security']['key'] ){
            throw new \collide\core\lib\internal\Exception( 'Default security key not changed. Change <code>$cfg[\'security\'][\'key\']</code> value from application config.' );
        }

        // check if Utils class has default name (prevent using by others)
        if( file_exists( APP_CONTROLLERS_PATH . 'collide' . EXT ) ){
            throw new \collide\core\lib\internal\Exception( '<code>collide.php</code> controller has the default name! Delete this controller or rename it (if you rename don\'t forget to rename the class name too).' );
        }
    }
}

if( !function_exists( 'initHook' ) ){
    /**
     * Instantiate controller and required libraries and call requested methods
     *
     * @access  public
     * @return  boolean true on success false on error
     * @TODO    split in small functions ore move functionality to controller
     */
    function initHook(){
	
    }
}

if( !function_exists( 'incLib' ) ){
    /**
     * Include standard and custom libraries.
     *
     * @access  public
     * @param   string  $name    library name
     * @return  mixed   false on error or class name on success
     */
    function incLib( $name ){
	require_once( CORE_LIB_INT_PATH . 'exception' . EXT );

	// include application config
	$appConfig = APP_CONFIG_PATH . 'config' . EXT;
        if( file_exists( $appConfig ) ){
            require( $appConfig );
        }else{
            throw new \collide\core\lib\internal\Exception(
		"Cannot find application config file <code>{$appConfig}</code>"
	    );
        }

        // prepare library name
	// class name begins with capital letter
        $name = trim( strtolower( $name ) );
        $className = ucfirst( $name );

	/**
	 * @todo regex to check name
	 */
        if( empty( $name ) ){
            throw new \collide\core\lib\internal\Exception(
		'Invalid library name!'
	    );
        }

        // include requested standard library first
        if( file_exists( CORE_LIB_INT_PATH . $name . EXT ) ){
            require_once( CORE_LIB_INT_PATH . $name . EXT );
        }else{
            throw new \collide\core\lib\internal\Exception(
		'Standard library not found!'
	    );
        }

        // include requested custom library if exists
        if( file_exists( APP_LIB_PATH . $cfg['default']['lib_prefix'] .
                         $name . EXT ) ){
            require_once( APP_LIB_PATH . $cfg['default']['lib_prefix'] .
                          $name . EXT );
            $className = $cfg['default']['lib_prefix'] . $className;
        }

        return $className;
    }
}

// first set display errors based on environment mode set in app config
setDisplayErrors();

// check for framework fatal errors
checkMvc();

// call initialization hook
initHook();