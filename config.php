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

//OAuth
define('ORCID_OAUTH_CLIENT_ID', '0000-0003-0387-8057');
define('ORCID_OAUTH_CLIENT_SECRET', 'e83c29f2-121d-444f-895a-891af6b7d5c8');
define('ORCID_OAUTH_REDIRECT_URI', 'http://disaggregator.asdf.ecs.soton.ac.uk/orcidcallback.php');
define('ORCID_OAUTH_AUTHORIZATION_URL', 'https://sandbox.orcid.org/oauth/authorize');
define('ORCID_OAUTH_TOKEN_URL', 'https://api.sandbox.orcid.org/oauth/token');
define('ORCID_OAUTH_API', 'https://api.sandbox.orcid.org/v1.1/');

require( CLASS_PATH . "/Reference.php" );
require( CLASS_PATH . "/Reaction.php");
require( CLASS_PATH . "/Compound.php");
require( CLASS_PATH . "/Tag.php");
require( CLASS_PATH . "/CompoundTag.php");
require( CLASS_PATH . "/ReactionTag.php");
require( CLASS_PATH . "/Spectra.php");
require( CLASS_PATH . "/User.php");
require( CLASS_PATH . "/Contributor.php");
require( CLASS_PATH . "/CompoundContributor.php");
require( CLASS_PATH . "/ReactionContributor.php");

require( CLASS_PATH . "/OpenXmlReader.php");

include "debug/ChromePhp.php";

function handleException( $exception ) {
  echo "Sorry, a problem occurred. Please try later.";
  error_log( $exception->getMessage() );
}
 
set_exception_handler( 'handleException' );

?>
