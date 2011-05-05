<?php if( !defined( 'ROOT_PATH' ) ) die( '403: Forbidden' );

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
 * Initialize requested controller
 *
 * @package     Collide MVC Core
 * @subpackage  Bootstrap
 * @category    Initialization
 * @author      Collide Applications Development Team
 * @link        http://mvc.collide-applications.com/docs/
 */

if( !function_exists( 'setDisplayErrors' ) ){
    /**
     * Check environment type and display or log errors
     *
     * @access  public
     * @return  void
     */
    function setDisplayErrors(){
        if( trim( strtolower( ENVIRONMENT ) ) == 'dev' ){   // dev
            // display all errors
            error_reporting( E_ALL );
            ini_set( 'display_errors', 'On' );
        }else{                                              // prod
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
     * Check if Collide MVC is prepared
     * e.g:
     * - security key changed;
     * - utils controller has the default name;
     *
     * @access  public
     * @return  void
     */
    function checkMvc(){
        incLib( 'collide_exception' );

        // check if default security key was changed
        require( APP_CONFIG_PATH . 'config.php' );
        if( isset( $cfg['security']['key'] ) && hash( 'md5', 'Collide MVC' ) == $cfg['security']['key'] ){
            throw new Collide_exception( 'Default security key not changed. Change <code>$cfg[\'security\'][\'key\']</code> value from application config.' );
        }

        // check if Utils class has default name (prevent using by others)
        if( file_exists( APP_CONTROLLERS_PATH . 'collide' . EXT ) ){
            throw new Collide_exception( '<code>collide.php</code> controller has the default name! Delete this controller or rename it (if you rename don\'t forget to rename the class name too).' );
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
     * Include standard and custom libraries
     *
     * @access  public
     * @param   string  $libName    library name
     * @return  mixed   false on error or class name on success
     */
    function incLib( $libName ){
        
    }
}

// first set display errors based on environment mode set in app config
setDisplayErrors();

// check for framework fatal errors
checkMvc();

// call initialization hook
initHook();