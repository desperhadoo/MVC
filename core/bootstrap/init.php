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

if( !function_exists( 'loadRequiredFiles' ) ){
    /**
     * Load required libraries and helpers.
     *
     * @access public
     * @return void
     */
    function loadRequiredFiles(){
	// include main Collide MVC class
        if( file_exists( CORE_BOOTSTRAP_PATH . 'collide' . EXT ) ){
            require_once( CORE_BOOTSTRAP_PATH . 'collide' . EXT );
        }else{
	    die( 'Fatal error (main library is missing)!' );
	}

	// include custom exception library from core
        if( file_exists( CORE_LIB_INT_PATH . 'exception' . EXT ) ){
            require_once( CORE_LIB_INT_PATH . 'exception' . EXT );
        }else{
	    die( 'Fatal error (exception library is missing)!' );
	}

	// include custom exception library from app if defined
        if( file_exists( APP_LIB_PATH . 'exception' . EXT ) ){
            require_once( APP_LIB_PATH . 'exception' . EXT );
        }

	// include log library from core
        if( file_exists( CORE_LIB_INT_PATH . 'log' . EXT ) ){
            require_once( CORE_LIB_INT_PATH . 'log' . EXT );
        }else{
	    throw new \collide\core\lib\internal\Exception(
		'Cannot find log library
		<code>./core/lib/internal/log.php</code>'
	    );
	}

	// include log library from app if defined
        if( file_exists( APP_LIB_PATH . 'log' . EXT ) ){
            require_once( APP_LIB_PATH . 'log' . EXT );
        }

	// include log helper
	if( file_exists( CORE_HELPERS_PATH . 'log' . EXT ) ){
	    require_once( CORE_HELPERS_PATH . 'log' . EXT );
	}else{
	    throw new \collide\core\lib\internal\Exception(
		'Cannot find log helper
		<code>./core/helpers/log.php</code>'
	    );
	}
    }
}

if( !function_exists( 'check' ) ){
    /**
     * Check if Collide MVC is prepared.
     * e.g:
     * - security key changed;
     * - utils controller has the default name;
     *
     * @access  public
     * @return  void
     */
    function check(){
        inc( 'exception' );

        // check if default security key was changed
        require( APP_CONFIG_PATH . 'config.php' );
        if( isset( $cfg['security']['key'] ) &&
	    hash( 'md5', 'Collide MVC' ) == $cfg['security']['key'] ){
            throw new \collide\core\lib\internal\Exception(
		'Default security key not changed. Change
		<code>$cfg[\'security\'][\'key\']</code>
		value from application config.'
	    );
        }

        // check if Utils class has default name (prevent using by others)
        if( file_exists( APP_CONTROLLERS_PATH . 'collide' . EXT ) ){
            throw new \collide\core\lib\internal\Exception(
		'<code>collide.php</code> controller has the default name!
		Delete this controller or rename it (if you rename don\'t forget
		to rename the class name too).'
	    );
        }
    }
}

if( !function_exists( 'init' ) ){
    /**
     * Instantiate controller and required libraries and call requested methods
     *
     * @access  public
     * @return  boolean true on success false on error
     * @TODO    split in small functions ore move functionality to controller
     */
    function init(){
	// include app main config
	if( file_exists( APP_CONFIG_PATH . 'config' . EXT ) ){
	    require_once( APP_CONFIG_PATH . 'config' . EXT );
	}else{
	    throw  new \collide\core\lib\internal\Exception(
		'Cannot find application main config file
		<code>./app/config/config.php</code>'
	    );
	}

        // set default values
        $controller	= $cfg['default']['controller'];
        $method		= $cfg['default']['method'];

        // get url segments
        $arrUrl = explode( '/', URL );

        // include standard controller library
        inc( 'controller' );

        // get controller
        if( count( $arrUrl ) ){
            $controllerPath = '';

            // get each url segment and check if it is a file or folder
            // if it is a file include it, if it is a folder go further until
            // a file is reached and include it
            foreach( $arrUrl as $urlSegment ){
                $urlSegment = strtolower( $urlSegment );

                // check if this segment is file and include it
                if( isset( $urlSegment ) && !empty( $urlSegment ) ){
                    if( file_exists( APP_CONTROLLERS_PATH .
                        $controllerPath . $urlSegment . EXT ) ){
                        require_once(
                            APP_CONTROLLERS_PATH .
                            $controllerPath . $urlSegment . EXT
                        );

                        // keep file name to use it in class name
                        $controller = $urlSegment;
                        array_shift( $arrUrl );

                        break;
                    }else if( is_dir( APP_CONTROLLERS_PATH . $urlSegment ) ){
                        // if this is a folder keep it and go further
                        $controllerPath .= $urlSegment . '/';
                        array_shift( $arrUrl );
                    }else{
                        // if no file or folder the url is incorrect
                        throw new Collide_exception( 'Page not found!' );
                    }
                }else{
                    // if no segment provided include default controller
                    if( file_exists( APP_CONTROLLERS_PATH . $controller . EXT ) ){
                        require_once( APP_CONTROLLERS_PATH . $controller . EXT );
                    }else{
                        // if default controller not found the url is incorrect
                        throw new Collide_exception( 'Page not found!' );
                    }
                }
            }
        }

        // get method
        if( isset( $arrUrl[0] ) && !empty( $arrUrl[0] ) ){
            $method = $arrUrl[0];
            array_shift( $arrUrl );
        }

        // query string
        $params = $arrUrl;

        // instantiate controller
        $controllerClassName = ucfirst( $controller ) . $cfg['default']['controller_sufix'];

        // if class exists instantiate it
        if( class_exists( $controllerClassName ) ){
            $objController = new $controllerClassName();
        }else{
            throw new Collide_exception( 'Page not found!' );
        }

        // try to call method
        if( (int)method_exists( $controllerClassName, $method ) ){
            call_user_func_array( array( $objController, $method ), $params );
        }else{
            throw new Collide_exception( 'Page not found!' );
        }

        return true;
    }
}

if( !function_exists( 'inc' ) ){
    /**
     * Include standard and custom libraries.
     *
     * @access  public
     * @param   string  $name    library name
     * @return  mixed   false on error or class name on success
     */
    function inc( $name ){
	require_once( CORE_LIB_INT_PATH . 'exception' . EXT );

	// include application config
	$appConfig = APP_CONFIG_PATH . 'config' . EXT;
        if( file_exists( $appConfig ) ){
            require( $appConfig );
        }else{
            throw new \collide\core\lib\internal\Exception(
		'Cannot find application config file
		<code>./app/config/config.php</code>'
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

// load required libraries and helpers
loadRequiredFiles();

// check for framework fatal errors
check();

// call initialization hook
init();