<?php include "templates/include/header.php" ?>

<?php 

$user = User::getById($_SESSION['userid']);
$action = $_POST['action'];

if ($action == "Save"){
    
    $user->userName = $_POST['username'];
    $user->userEmail = $_POST['email'];
    $user->orcid = $_POST['orcid'];
       
    $user->update();
}
    
?>

<form action="view.php?type=user" method="post" style="width: 50%;">
    <input type="hidden" name="userForm" value="true" />
    
    <label for="username">Username: </label>
    <input id="username" type="text" name="username" readonly value="<?php echo $user->userName ?>" />

    <label for="email">Email address: </label>
    <input id="email" type="email" name="email" value="<?php echo $user->userEmail ?>" />
    
    <label for="orcid">Orcid: </label>
    <input id="orcid" type="text" name="orcid" value="<?php echo $user->orcid ?>"/>
    
    <input class="btn btn-default" type="submit" name="action" value="Save" />
    <input class="btn btn-default" type="button" name="cancel" value="Cancel" onClick="window.location='index.php';" /> 
</form>
    
<?php include "templates/include/footer.php" ?>

