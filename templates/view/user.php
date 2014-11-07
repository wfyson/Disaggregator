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

<div class='user-view'>

    <h2>Account</h2>

    <form class='user-form' action="view.php?type=user" method="post" style="width: 50%;">
        <input type="hidden" name="userForm" value="true" />

        <div class='form-row'>
            <label for="username">Username: </label>
            <input id="username" type="text" name="username" readonly value="<?php echo $user->userName ?>" />
        </div>

        <div class='form-row'>
            <label for="email">Email address: </label>
            <input id="email" type="email" name="email" value="<?php echo $user->userEmail ?>" />
        </div>

        <div class='form-row'>
            <label for="orcid">Orcid: </label>
            <input id="orcid" type="text" name="orcid" value="<?php echo $user->orcid ?>"/>
            
            <?php if(isset($user->orcid)){
                if(!isset($user->orcidCode)){ ?>
                    <a class='btn btn-primary btn-xs' href='publish.php?platform=orcid'>Authorise Access</a>
                <?php }else{ ?>
                    <span class='label label-primary'>Access Authorised</span>
                <?php }
            } ?>
        </div>

        <div class='form-buttons'>
            <input class="btn btn-default" type="submit" name="action" value="Save" />
            <input class="btn btn-default" type="button" name="cancel" value="Cancel" onClick="window.location='index.php';" /> 
        </div>
    </form>

</div>
    
<?php include "templates/include/footer.php" ?>

