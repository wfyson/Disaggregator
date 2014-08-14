<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set( "display_errors", true );

date_default_timezone_set( "Europe/London" );

define( "DB_DSN", "mysql:host=localhost;dbname=disaggregator" );
define("DB_HOST", "localhost");
define("DB_NAME", "disaggregator");
define("DB_USER", "disaggregator");
define("DB_PASS", "password");

define( "CLASS_PATH", "classes" );
define( "TEMPLATE_PATH", "templates" );

require( CLASS_PATH . "/Reference.php" );
require( CLASS_PATH . "/Reaction.php");
require( CLASS_PATH . "/Compound.php");
require( CLASS_PATH . "/CompoundReference.php");

require( CLASS_PATH . "/OpenXmlReader.php");

include "debug/ChromePhp.php";

function handleException( $exception ) {
  echo "Sorry, a problem occurred. Please try later.";
  error_log( $exception->getMessage() );
}
 
set_exception_handler( 'handleException' );

?>
