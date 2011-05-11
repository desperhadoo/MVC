<?php
namespace collide\app\config;

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
 * Default application config.
 *
 * @package     Collide MVC Core
 * @subpackage  Config
 * @category    Application config
 * @author      Collide Applications Development Team
 * @link        http://mvc.collide-applications.com/docs/
 */

/**
 * Security key used to concatenate on hashes.
 *
 * Default security key is MD5( 'Collide MVC' )
 * !!!OBS: It is recommended to change default security key
 */
$cfg['security']['key'] = 'c953a8af38791d0a6e0d5d7268152e561';

// default controller (called when controller is missing from url)
$cfg['default']['controller'] = 'blog';

// default method (called when method is missing from url)
$cfg['default']['method'] = 'index';

// default controller class name sufix
$cfg['default']['controller_sufix'] = 'Controller';

// default model class name sufix
$cfg['default']['model_sufix'] = 'Model';

// default library class name prefix
$cfg['default']['lib_prefix'] = '_';

// default view name
$cfg['default']['view'] = 'index';
