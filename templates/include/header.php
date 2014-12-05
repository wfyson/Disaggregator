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
        <script src="js/stages/contributorStage.js"></script>

    </head>
    <body>
        
        <div id="header">
            <nav class="navbar navbar-inverse" role="navigation">
                <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-9">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Disaggregator</a>
                </div>
<?php     
        
        $username = isset($_SESSION['username']) ? $_SESSION['username'] : "";        
        if ($username) { ?>
            <ul class="nav navbar-nav navbar-right">
                <?php
                $page = basename($_SERVER['PHP_SELF']);
                if ($page != "index.php"){
                    ?>
                <li>
                    <a href="index.php"><span class="glyphicon glyphicon-home"></span> Homepage</a>
                </li>
                    <?php
                }
                ?>
                
                <li>
                    <a href="view.php?type=user"><span class="glyphicon glyphicon-user"></span> Edit Profile</a>
                </li>
                <li>
                    <a href="index.php?action=logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a>
                </li>
            </ul>                        
    <?php
        } 
        ?>  </div>  
            </nav>
        </div>
        <div id="container" class="row">
