<?php
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
 * Log Helper
 *
 * @package     Collide MVC Core
 * @subpackage  Standard Helper
 * @category    Helpers
 * @author      Collide Applications Development Team
 * @link        http://mvc.collide-applications.com/docs/
 */

/**
 * Write to logs
 *
 * This helper calls Log::write() method and is used to avoid loading log object
 * in all classes
 *
 * @access  public
 * @param   string  $msg            message to log
 * @param   string  $level          log level (defined in config)
 * @param   mixed   $exclusiveTypes array or string with log types to write
 * @return  void
 */
if( !function_exists( 'logWrite' ) ){
    function logWrite( $msg, $level = 'info', $exclusiveTypes = null ){
        $log =& Log::getInstance();

        $log->write( $msg, $level, $exclusiveTypes );
    }
}