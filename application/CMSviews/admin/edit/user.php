<h1><?php echo $results['pageTitle']?></h1>
        <form method="post" 
      action="<?= \ItForFree\SimpleMVC\Router\WebRouter::link($results['users']->id ? 'admin/editUser' : 'admin/newUser') . ($results['users']->id ? '&userId=' . $results['users']->id : '') ?>">

    <?php if ($results['users']->id): ?>
        <input type="hidden" name="userId" value="<?= htmlspecialchars($results['users']->id, ENT_QUOTES, 'UTF-8') ?>" />
    <?php endif; ?>
    <?php if (isset($results['errorMessage'])) { ?>
            <div class="errorMessage"><?php 
                                          echo $results['errorMessage'] ?></div>
    <?php } ?>
            <ul>
              <li>
                <label for="title">User Login</label>
                <input type="text" name="login" id="login" 
                       placeholder="Login" 
                       required autofocus maxlength="25"/>
              </li>
              <li>
                <label for="title">User Password</label>
                <input type="text" name="password" id="password" 
                       placeholder="Password" 
                       required autofocus maxlength="25" />
              </li>
              
              <li>
                  <label for="checkActivity">Active</label>
                  <input type="checkbox" name="active" value="1" 
                         id="checkboxActivity"
                  <?php
                        if($results['users']->active == 1) {
                            echo 'checked = "checked"';
                        }
                  ?>
                  >
              </li>
            </ul>
            <div class="buttons">
              <input type="submit" name="saveChanges" value="Save Changes" />
              <input type="submit" formnovalidate name="cancel" value="Cancel" />
            </div>
        </form>
    <?php if ($results['users']->login) { ?>
          <p>
        <a href="<?= \ItForFree\SimpleMVC\Router\WebRouter::link('Admin/deleteUser') ?>&amp;userId=<?php echo $results['users']->id ?>" onclick="return confirm('Delete This Category?')">
            Delete This User
        </a>
          </p>
    <?php } ?>
