<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo htmlspecialchars($results['pageTitle']) ?></title>

        <!-- CSS -->
        <!-- Bootstrap -->
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>

        <!-- Page Styling -->
        <link rel="stylesheet" type="text/css" href="css/style.css" />

        <!-- Javascript -->  
        <!-- JQuery -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

         <!-- JSMol -->
        <script type="text/javascript" src="js/libs/jsmol/JSmol.min.js"></script>
        <script type="text/javascript" src="js/libs/jsmol/JSmol.lite.nojq.js"></script>
        
        <!-- Bootstrap -->
        <script src="js/libs/bootstrap.min.js"></script>
        
        <!-- My Stuff -->
        <script src="js/filehandler.js"></script>
        <script src="js/builder.js"></script>
        <script src="js/stages/textStage.js"></script>
        <script src="js/stages/imageStage.js"></script>
        <script src="js/stages/fileStage.js"></script>
        <script src="js/stages/compoundStage.js"></script>
        <script src="js/stages/selectStage.js"></script>

    </head>
    <body>
        
        <div id="header">
        <?php 
        //provide a link to homepage
        $page = basename($_SERVER['PHP_SELF']);
        if ($page != "index.php"){
            ?>
            <span class="homepage"><a class="btn btn-default" href="index.php">Return to Homepage</a></span>
            <?php
        }       
        
        $username = isset($_SESSION['username']) ? $_SESSION['username'] : "";
        ChromePhp::log($username);
        if ($username) {
            ChromePhp::log("logged in");
            //only provide log out link if a user is logged in
            ?>
            <span class="login-status">
            <span>Logged in as <b><?php echo $username; ?></b></span>

            <form action="index.php?action=logout" method="post" style="width: 50%;">
                <input type="hidden" name="logout" value="true" />   
                <input class="btn btn-default" type="submit" name="logout" value="Logout" />
            </form>
            </span>
    <?php
        } 
    ?>    
        </div>
        <div id="container" class="row">
