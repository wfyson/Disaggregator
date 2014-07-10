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

    </head>
    <body>

        <!--log out button here?? -->

        <!--logout button (should be in header perhaps...) -->
        <form action="index.php?action=logout" method="post" style="width: 50%;">
            <input type="hidden" name="logout" value="true" />   
            <input class="btn btn-default" type="submit" name="logout" value="Logout" />
        </form>

        <div id="container" class="row">
