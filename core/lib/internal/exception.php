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
 * Custom exception class
 *
 * @package     Collide MVC Core
 * @subpackage  Libraries
 * @category    Exception
 * @author      Collide Applications Development Team
 * @link        http://mvc.collide-applications.com/docs/
 */
class Exception extends \Exception{
    /**
     * Constructor
     *
     * @access  public
     * @param   string  $message exception message
     * @return  void
     */
    public function __construct( $message ){
        parent::__construct( $message, 0 );
    }

    /**
     * Show exception message
     *
     * @access  public
     * @return  void
     */
    public function  __toString(){
        $html =<<< EOT
<html>
    <head>
        <title>Error</title>
        <style type="text/css">
            div#error{
                border:1px solid #dddddd;
                font-family: Verdana, Arial;
                font-size:16px;
                color:#333333;
                padding:10px;
                -moz-border-radius:5px;     /* round corners (Firefox) */
                -webkit-border-radius:5px;  /* round corners (Webkit) */
                text-shadow: #aaa 1px 1px 1px;
                -moz-box-shadow: 2px  2px 3px #969696;      /* box shadow (Firefox) */
                -webkit-box-shadow: 2px 2px 3px #969696;    /* box shadow (Webkit) */
                background-image: -moz-linear-gradient(top, #ffffff, #dddddd);                                                  /* gradient (Firefox) */
                background-image: -webkit-gradient(linear,left bottom,left top,color-stop(0, #dddddd),color-stop(1, #ffffff));  /* gradient (Webkit) */
            }
            div#error div#title{
                font-weight:bold;
                font-size:1.1em;
                border-bottom:1px dashed #ff0000;
                padding-bottom:5px;
                margin-bottom:5px;
            }
            div#error div#title a{
                color:#333333;
                text-decoration:none;
            }
            div#error div#title a:hover{
                color:#f70;
            }
            div#error span#message{
                font-weight:bold;
                color:#e00000;
            }
            div#error code{
                color:#0000ff;
                font-size:1.1em;
            }
        </style>
    </head>
    <body>
        <div id="error">
            <div id="title"><a href="http://mvc.collide-applications.com">Collide MVC Framework</a></div>
            <span id="message">Error: </span>
EOT;

        $html .= parent::getMessage();

        $html .=<<< EOT

        </div>
    </body>
</html>
EOT;

        die( $html );
    }
}
