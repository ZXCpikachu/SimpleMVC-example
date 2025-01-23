	  
<form action="<?= \ItForFree\SimpleMVC\Router\WebRouter::link('Login/login')?>" method="post" style="width: 50%;">
        <input type="hidden" name="login" value="true" />

        <?php if (isset($errorMessage)) { ?>
        <div class="errorMessage"><?php echo $errorMessage; ?></div>
    <?php } ?>

        <ul>

            <li>
                <label for="username">Username</label>
                <input type="text" name="userName" id="userName" placeholder="Your admin username" required autofocus maxlength="20" />
            </li>

            <li>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Your admin password" required maxlength="20" />
            </li>

        </ul>

        <div class="buttons">
            <input type="submit" name="login" value="Войти" />
        </div>

    </form>


