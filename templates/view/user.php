<?php include "templates/include/header.php" ?>

<?php 

$user = User::getById($_SESSION['userid']);
$contributor = $user->getContributor();

$action = $_POST['action'];

if ($action == "Save"){
    
    $user->userName = $_POST['username'];
    $user->userEmail = $_POST['email'];               
    $user->update();
    
    $contributor->orcid = $_POST['orcid'];
    $contributor->update();
}
    
?>

<div class='user-view'>

    <h2>Account</h2>

    <form role="form" action="view.php?type=user" method="post" style="width: 50%;">
        <input type="hidden" name="userForm" value="true" />

        <div class='form-group'>
            <label for="username">Username: </label>
            <input id="username" class="form-control" type="text" name="username" readonly value="<?php echo $user->userName ?>" />
        </div>

        <div class='form-group'>
            <label for="email">Email address: </label>
            <input id="email" class="form-control" type="email" name="email" value="<?php echo $user->userEmail ?>" />
        </div>

        <!--orcid -->  
        <div class='form-group'>
            <label for="orcid">Orcid: </label>
            <input id="orcid" class="form-control" type="text" name="orcid" value="<?php echo $contributor->orcid ?>"/>
            
            <?php if(isset($contributor->orcid)){
                if(!isset($contributor->orcidCode)){ ?>
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

