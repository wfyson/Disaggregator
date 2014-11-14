<?php include "templates/include/header.php" ?>

<div id="login-form">

    <div id="login">

        <h2>Login</h2>

        <form role="form" action="index.php?action=login" method="post" style="width: 50%;">
            <input type="hidden" name="login" value="true" />

            <?php if (isset($results['errorMessage'])) { ?>
                <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
            <?php } ?>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="Your username" autofocus maxlength="20" />
                </div>
                
                    <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Your password" maxlength="20" />
                    </div>
            <input class="btn btn-default" type="submit" name="login" value="Login" />
        </form>


        <div id="register">
            <h4>No account..?</h4> 
            <form action="index.php?action=register" method="post" style="width: 50%;">
                <input type="hidden" name="registerform" value="true" />
                <input class="btn btn-default" type="submit" name="registerform" value="Register" />
            </form>
        </div>

    </div>

</div>


<?php include "templates/include/footer.php" ?>