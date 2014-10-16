<?php

require_once("libraries/password_compatibility_library.php");
require("config.php");

session_start();
$action = isset($_GET['action']) ? $_GET['action'] : "";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

if ($action == "register") {
    register();
    exit;
}

if ($action == "registerOrcid") {
    registerOrcid();
    exit;
}


if ($action != "login" && $action != "logout" && !$username) {
    login();
    exit;
}

switch ($action) {
    case 'login':
        login();
        break;
    case 'logout':
        logout();
        break;
    case 'register':
        register();
        break;
    default:
        homepage();
}

function homepage() {
    $results = array();    
    $compounds = Compound::getList();
    $reactions = Reaction::getList();
    $references = Reference::getList();
    
    $results['compounds'] = $compounds['results'];
    $results['reactions'] = $reactions['results'];
    $results['references'] = $references['results'];
    $results['pageTitle'] = "Disaggregator - References";
    
    require( TEMPLATE_PATH . "/homepage.php" );
}

function login() {
    $results = array();
    $results['pageTitle'] = "Disaggregator | Login";

    if (isset($_POST['login'])) {
        //check form contents
        if (empty($_POST['username'])) {
            $results['errorMessage'] = "Incorrect username or password. Please try again. No username";
            require( TEMPLATE_PATH . "/loginForm.php" );
            exit;
        } elseif (empty($_POST['password'])) {
            $results['errorMessage'] = "Incorrect username or password. Please try again.";
            require( TEMPLATE_PATH . "/loginForm.php" );
            exit;
        } elseif (!empty($_POST['username']) && !empty($_POST['password'])) {
            $conn = new PDO(DB_DSN, DB_USER, DB_PASS);

            $username = $_POST['username'];
            $sql = "SELECT user_id, user_name, user_email, user_password_hash
                    FROM users
                    WHERE user_name = :user_name";
            $st = $conn->prepare($sql);
            $st->bindValue(":user_name", $username, PDO::PARAM_STR);
            $st->execute();
            $row = $st->fetch();
            $conn = null;
            if ($row) {
                if (password_verify($_POST['password'], $row[user_password_hash])) {
                    // write user data into PHP SESSION (a file on your server)
                    $_SESSION['userid'] = $row[user_id];                    
                    $_SESSION['username'] = $row[user_name];
                    header("Location: index.php");
                } else {
                    $results['errorMessage'] = "Wrong password. Try again.";
                    require( TEMPLATE_PATH . "/loginForm.php" );
                    exit;
                }
            } else {
                $results['errorMessage'] = "This user does not exist.";
                require( TEMPLATE_PATH . "/loginForm.php" );
                exit;
            }
        }
    } else {
        // User has not posted the login form yet: display the form
        require( TEMPLATE_PATH . "/loginForm.php" );
    }
}

function logout() {
    unset($_SESSION['userid']);
    unset($_SESSION['username']);
    header("Location: index.php");
}

function register() {
    $results = array();
    $results['pageTitle'] = "Disaggregator | Registration";
    if (isset($_POST['register'])) {
        if (empty($_POST['user_name'])) {
            $results['errorMessage'] = "Empty Username";
            require( TEMPLATE_PATH . "/register.php" );
            exit;
        } elseif (empty($_POST['user_password_new']) || empty($_POST['user_password_repeat'])) {
            $results['errorMessage'] = "Empty Password";
            require( TEMPLATE_PATH . "/register.php" );
            exit;
        } elseif ($_POST['user_password_new'] !== $_POST['user_password_repeat']) {
            $results['errorMessage'] = "Password and password repeat are not the same";
            require( TEMPLATE_PATH . "/register.php" );
            exit;
        } elseif (strlen($_POST['user_password_new']) < 6) {
            $results['errorMessage'] = "Password has a minimum length of 6 characters";
            require( TEMPLATE_PATH . "/register.php" );
            exit;
        } elseif (strlen($_POST['user_name']) > 64 || strlen($_POST['user_name']) < 2) {
            $results['errorMessage'] = "Username cannot be shorter than 2 or longer than 64 characters";
            require( TEMPLATE_PATH . "/register.php" );
            exit;
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])) {
            $results['errorMessage'] = "Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters";
            require( TEMPLATE_PATH . "/register.php" );
            exit;
        } elseif (strlen($_POST['user_email']) > 64) {
            $results['errorMessage'] = "Email cannot be longer than 64 characters";
            require( TEMPLATE_PATH . "/register.php" );
            exit;
        } elseif (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
            $results['errorMessage'] = "Your email address is not in a valid email format";
            require( TEMPLATE_PATH . "/register.php" );
            exit;
        } elseif (!empty($_POST['user_name']) && strlen($_POST['user_name']) <= 64 && strlen($_POST['user_name']) >= 2 && preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name']) && !empty($_POST['user_email']) && strlen($_POST['user_email']) <= 64 && filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL) && !empty($_POST['user_password_new']) && !empty($_POST['user_password_repeat']) && ($_POST['user_password_new'] === $_POST['user_password_repeat'])
        ) {
            ChromePhp::log("everything checks out");
            //create database connection
            $conn = new PDO(DB_DSN, DB_USER, DB_PASS);

            $username = $_POST['user_name'];
            $useremail = $_POST['user_email'];

            // check if user or email address already exists
            $sql = "SELECT * FROM users WHERE user_name = :username OR user_email = :useremail";
            $st = $conn->prepare($sql);
            $st->bindValue(":username", $username, PDO::PARAM_STR);
            $st->bindValue(":useremail", $useremail, PDO::PARAM_STR);
            $st->execute();
            $row = $st->fetch();
            if (count($row) === 1) {
                //username not already registered, so register it
                $user_password = $_POST['user_password_new'];
                // crypt the user's password with PHP 5.5's password_hash() function, results in a 60 character
                // hash string. the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using
                // PHP 5.3/5.4, by the password hashing compatibility library
                $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);

                $sql = "INSERT INTO users (user_name, user_password_hash, user_email)
                            VALUES(:username, :userpassword, :useremail);";

                $st = $conn->prepare($sql);
                $st->bindValue(":username", $username, PDO::PARAM_STR);
                $st->bindValue(":userpassword", $user_password_hash, PDO::PARAM_STR);
                $st->bindValue(":useremail", $useremail, PDO::PARAM_STR);

                $insert = $st->execute();
                if ($insert) {
                    $results['errorMessage'] = "Your account has been created successfully. You can now log in.";
                    require( TEMPLATE_PATH . "/loginForm.php" );
                    exit;
                } else {
                    $results['errorMessage'] = "Sorry, your registration failed. Please go back and try again.";
                }
            } else {
                $results['errorMessage'] = "Sorry that username/email address is already in use.";
                require( TEMPLATE_PATH . "/register.php" );
                exit;
            }
        }
    } else {
        // User has not posted the login form yet: display the form
        require( TEMPLATE_PATH . "/register.php" );
    }
}

function registerOrcid(){
    
    //useful php curl orcid link: https://gist.github.com/hubgit/46a868b912ccd65e4a6b
    
    
    $orcid = $_POST['orcid'];
    $url = "http://pub.orcid.org/v1.1/" . $orcid . "/orcid-bio";
   
    $ch = curl_init();
    
    $options = array(
      CURLOPT_URL => $url,  
      CURLOPT_HTTPHEADER => array('Accept: application/orcid+json'),
      CURLOPT_RETURNTRANSFER => true
    );
    
    curl_setopt_array($ch, $options);
       
    $result = curl_exec($ch);
       
    $response = json_decode($result, true);
    
    $given = $response['orcid-profile']['orcid-bio']['personal-details']['given-names']['value'];
    $family = $response['orcid-profile']['orcid-bio']['personal-details']['family-name']['value'];
    $email = $response['orcid-profile']['orcid-bio']['contact-details']['email'][0]['value'];
        
    print_r($response);
}