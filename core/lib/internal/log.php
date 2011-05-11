<?php
namespace collide\core\lib\internal;

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
 * Logs library
 *
 * Provides methods to work with log files, FirePHP logs and email logs
 *
 * @package     Collide MVC Core
 * @subpackage  Libraries
 * @category    Log
 * @author      Collide Applications Development Team
 * @link        http://mvc.collide-applications.com/docs/
 */
class Log{
    /**
     * This instance
     *
     * @access  private
     * @var     object  this instance
     */
    private static $_instance;

    /**
     * Application config array
     *
     * @access  private
     * @var     array   $_cfg   config array
     */
    private $_cfg = array();

    /**
     * Log levels
     *
     * @access  private
     * @var     array   $_levels    log levels
     */
    private $_levels = array(
        'core'      => 0,
        'info'      => 1,
        'warning'   => 2,
        'error'     => 3,
        'debug'     => 4
    );

    /**
     * Message to write
     *
     * This is set in "write" method
     *
     * @access  private
     * @var     string  $_msg   message to write
     */
    private $_msg;

    /**
     * Message level
     *
     * This is set in "write" method
     *
     * @access  private
     * @var     string  $_level message level
     */
    private $_level;

    /**
     * FirePHP instance
     *
     * @access  private
     * @var     object  $_firephp   FirePHP instance
     */
    private $_firephp;

    /**
     * Constructor
     *
     * @access  public
     * @return  void
     */
    public function __construct(){
        self::$_instance =& $this;

        // load config file
        require( APP_CONFIG_PATH . 'config' . EXT );
        if( isset( $cfg ) ){
            $this->_cfg = $cfg['log'];
            unset( $cfg );
        }

        // initialize FirePHP
        $this->initFirePhp();

        // initialize ChromePHP
        $this->initChromePhp();
    }

    /**
     * Return this instance
     *
     * @access  public
     * @return  object  this instance reference
     */
    public static function &getInstance(){
        return self::$_instance;
    }

    /**
     * Initialize FirePHP class
     *
     * @access  private
     * @return  void
     */
    private function initFirePhp(){
        // initialize only if it is enabled from config
        if( count( $this->_cfg['types']['firephp']['level'] ) ){
            // include FirePHP external library for firephp log type
            require_once( CORE_LIB_EXT_PATH . 'FirePHPCore-0.3.1/FirePHP.class.php' );

            // get instance of FirePHP
            $this->_firephp = FirePHP::getInstance( true );
            // create group
            $this->_firephp->group(
                'Collide MVC Log',
                array(
                    'Collapsed' => $this->_cfg['types']['firephp']['options']['collapsed'],
                    'Color' => '#FF0000'
                )
            );
        }
    }

    /**
     * Initialize ChromePhp class
     *
     * @access  private
     * @return  void
     */
    private function initChromePhp(){
        // initialize only if it is enabled from config
        if( count( $this->_cfg['types']['firephp']['level'] ) ){
            require_once( CORE_LIB_EXT_PATH . 'ChromePHP-2.2.1/ChromePhp.php' );
        }
    }

    /**
     * Write to logs
     *
     * This method writes to all log types activated in config
     *
     * @access  public
     * @param   string  $msg            message to log
     * @param   string  $level          log level (defined in config)
     * @param   mixed   $exclusiveTypes array or string with log types to write
     * @return  void
     */
    public function write( $msg, $level = 'info', $exclusiveTypes = null ){
        // set parameters
        $this->_msg     = $msg;
        $this->_level   = strtolower( trim( $level ) );

        // get log types
        $logTypes = $this->_cfg['types'];

        // check if at least one log was writed
        $writed = false;

        if( !is_null( $exclusiveTypes ) && !is_array( $exclusiveTypes ) ){
            $exclusiveTypes = array( $exclusiveTypes );
        }

        // for each enabled type try to write log
        foreach( $logTypes as $type => $properties ){
            if( is_array( $exclusiveTypes ) && !in_array( $type, $exclusiveTypes ) ){
                continue;
            }

            // write logs for specified levels
            if( count( $properties['level'] ) && in_array( $this->_levels[$this->_level], $properties['level'] ) ){
                // check if method exists for each type
                if( method_exists( $this, $type ) ){
                    $options = array();

                    // set options if any
                    if( isset( $properties['options'] ) ){
                        $options = $properties['options'];
                    }

                    // call method
                    $this->$type( $options );
                }
            }
        }
    }

    /**
     * Write logs to files
     *
     * Each day will have its own log
     *
     * @access  private
     * @param   array   $opt    options for this log type
     * @return  void
     */
    private function file( $opt = array() ){
        // check if folder is writable and do nothing otherwise
        $dir = @opendir( CORE_LOG_PATH );
        if( $dir === false ){
            throw new Collide_exception( 'Logs dir is not readable!' );
        }else{
            closedir( $dir );
        }

        $file = @fopen( CORE_LOG_PATH . 'test_writable', 'w' );
        if( $file === false ){
            throw new Collide_exception( 'Logs dir is not writable!' );
        }else{
            fclose( $file );
            @unlink( CORE_LOG_PATH . 'test_writable' );
        }

        // text to write
        $text = '';

        // file to write to
        $fileName = CORE_LOG_PATH . date( 'm_d_Y' ) . '.php';

        // write message
        $logNew = $this->_cfg['new'];
        $openType = 'a';
        if( $logNew ){
            $openType = 'w';
        }

        if( !file_exists( $fileName ) || $logNew ){
            $text .= <<<EOF
<?php if( !defined( 'ROOT_PATH' ) ) die( NO_ACCESS_MSG );

/******************************************************************************
 *                                                                            *
 * Collide MVC Framework                                                      *
 *                                                                            *
 * Log file generated automaticaly                                            *
 *                                                                            *
 ******************************************************************************/


EOF;
        }else{
            // check if existent file is writable and do nothing otherwise
            if( !is_writable( $fileName ) ){
                throw new Collide_exception( 'Log file is not writable!' );
            }
        }

        // create message line
        $text .= date( 'h:i:s - ' ) . ucfirst( $this->_level ) . ' - ' . $this->_msg . "\n";

        // write to log file
        $fp = @fopen( $fileName, $openType );
        if( $fp === false ){
            throw new Collide_exception( 'Cannot open log file!' );
        }

        fwrite( $fp, $text );
        fclose( $fp );
    }

    /**
     * Send log on email
     *
     * @access  private
     * @param   array   $opt    options for this log type
     * @return  void
     * @todo    to be implemented when emailing sistem is done
     */
    private function email( $opt = array() ){
        /**
         * @todo Implement this
         */
    }

    /**
     * Display logs in Firebug console
     *
     * FirePHP addon for Firefox required
     * This method is using FirePHP library
     *
     * @access  private
     * @param   array   $opt    options for this log type
     * @return  void
     */
    private function firephp( $opt = array() ){
        // if initialized
        if( count( $this->_cfg['types']['firephp']['level'] ) ){
            // set options if any
            if( is_array( $opt ) ){
                $this->_firephp->getOptions();
                $this->_firephp->setOptions( $opt );
            }

            // call log function
            switch( $this->_level ){
                case 'info':
                    $this->_firephp->info( $this->_msg );
                    break;
                case 'warning':
                    $this->_firephp->warn( $this->_msg );
                    break;
                case 'error':
                    $this->_firephp->error( $this->_msg );
                    break;
                default:
                    // other log levels
                    $this->_firephp->log( $this->_msg, strtoupper( $this->_level ) );
            }

            // add trace if set in config
            if( isset( $opt['trace'] ) && $opt['trace'] === true ){
                $this->_firephp->trace( 'Trace' );
            }
        }
    }

    /**
     * Display logs in Chrome console
     *
     * ChromePHP addon for Chrome required
     * This method is using ChromePHP library
     *
     * @access  private
     * @param   array   $opt    options for this log type
     * @return  void
     */
    private function chromephp( $opt = array() ){
        // if initialized
        if( count( $this->_cfg['types']['firephp']['level'] ) ){
            // call log function
            switch( $this->_level ){
                case 'warning':
                    ChromePhp::warn( $this->_msg );
                    break;
                case 'error':
                    ChromePhp::error( $this->_msg );
                    break;
                default:
                    // info or other log levels
                    ChromePhp::log( $this->_msg );
            }
        }
    }
}