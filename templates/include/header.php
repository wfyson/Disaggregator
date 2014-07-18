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

        <!-- My Stuff -->
        <script src="js/filehandler.js"></script>

        <!-- Bootstrap -->
        <script src="js/libs/bootstrap.min.js"></script>
        
        <!-- JSMol -->
        <script type="text/javascript" src="js/libs/jsmol/JSmol.min.js"></script>
        <script type="text/javascript" src="js/libs/jsmol/JSmol.lite.nojq.js"></script>

    </head>
    <body>
        <div id="header">
        <?php
        $username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

        if (!$username) {
            
        } else {
            //only provide log out link if user is logged in
            ?>
            <span>Logged in as <b><?php echo $username; ?></b></span>

            <form action="index.php?action=logout" method="post" style="width: 50%;">
                <input type="hidden" name="logout" value="true" />   
                <input class="btn btn-default" type="submit" name="logout" value="Logout" />
            </form>
    <?php
        }
        //provide a link to homepage
        $page = basename($_SERVER['PHP_SELF']);
        if ($page != "index.php"){
            ?>
            <a class="btn btn-default" href="index.php">Return to Homepage</a>
            <?php
        }
        
        
        
        
    ?>    
        </div>
        <div id="container" class="row">
