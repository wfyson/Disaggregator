<?php include "templates/include/header.php" ?>

<form action="index.php?action=register" method="post" style="width: 50%;">
    <input type="hidden" name="registerForm" value="true" />

    <?php if (isset($results['errorMessage'])) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

    <!-- the user name input field uses a HTML5 pattern check -->
    <label for="login_input_username">Username (only letters and numbers, 2 to 64 characters): </label>
    <input id="login_input_username" class="login_input" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" required />

    <!-- the name of the user to be used when selecting contributors -->
    <label for="login_name">Name: </label>
    <input id="login_name" class="login_input" type="text" name="name" required />
    
    <!-- the email input field uses a HTML5 email type check -->
    <label for="login_input_email">User's email: </label>
    <input id="login_input_email" class="login_input" type="email" name="user_email" required />

    <label for="login_input_password_new">Password (min. 6 characters): </label>
    <input id="login_input_password_new" class="login_input" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" />

    <label for="login_input_password_repeat">Repeat password: </label>
    <input id="login_input_password_repeat" class="login_input" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />
    
    
    <input class="btn btn-default" type="submit"  name="register" value="Register" />
</form>

<?php include "templates/include/footer.php" ?>

