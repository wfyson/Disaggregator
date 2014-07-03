<?php include "templates/include/header.php" ?>

<form action="index.php?action=login" method="post" style="width: 50%;">
    <input type="hidden" name="login" value="true" />

    <?php if (isset($results['errorMessage'])) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

    <ul>

        <li>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" placeholder="Your username" autofocus maxlength="20" />
        </li>

        <li>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Your password" maxlength="20" />
        </li>

    </ul>


    <input class="btn btn-default" type="submit" name="login" value="Login" />


</form>

<form action="index.php?action=register" method="post" style="width: 50%;">
    <input type="hidden" name="registerform" value="true" />
    <input class="btn btn-default" type="submit" name="registerform" value="Register" />
</form>

<?php include "templates/include/footer.php" ?>