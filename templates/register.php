<?php include "templates/include/header.php" ?>

<div id="register-form">

    <h2>Register</h2>

    <form role="form" action="index.php?action=register" method="post" style="width: 60%;">
        <input type="hidden" name="registerForm" value="true" />

        <?php if (isset($results['errorMessage'])) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
        <?php } ?>

        <!-- the user name input field uses a HTML5 pattern check -->
        <div class="form-group">
            <label for="login_input_username">Username* (only letters and numbers, 2 to 64 characters): </label>
            <input id="login_input_username" class="form-control" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" required />
        </div>

        <!-- the name of the user to be used when selecting contributors -->
        <div class="form-group">
            <label for="login_first_name">First Name*: </label>
            <input id="login__first_name" class="form-control" type="text" name="first_name" required />
        </div>
        
        <div class="form-group">
            <label for="login_family_name">Family Name*: </label>
            <input id="login__family_name" class="form-control" type="text" name="family_name" required />
        </div>
        
        <div class="form-group">
            <label for="login_orcid">Orcid: </label>
            <input id="login__orcid" class="form-control" type="text" name="orcid" />
        </div>

        <!-- the email input field uses a HTML5 email type check -->
        <div class="form-group">
            <label for="login_input_email">User's email*: </label>
            <input id="login_input_email" class="form-control" type="email" name="user_email" required />
        </div>

        <div class="form-group">
            <label for="login_input_password_new">Password* (min. 6 characters): </label>
            <input id="login_input_password_new" class="form-control" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" />
        </div>

        <div class="form-group">
            <label for="login_input_password_repeat">Repeat password*: </label>
            <input id="login_input_password_repeat" class="form-control" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />
        </div>

        <input class="btn btn-default" type="submit"  name="register" value="Register" />
    </form>

</div>

<?php include "templates/include/footer.php" ?>

