<?php
namespace application\controllers;

use ItForFree\SimpleMVC\Config;
use ItForFree\SimpleMVC\Router\WebRouter; // Подключаем WebRouter

class LoginController extends \ItForFree\SimpleMVC\MVC\Controller
{
    public string $layoutPath = 'main.php';
    public $title = 'Admin Login';
    public $errorMessage = 'Неправильный логин или пароль';

    public function loginAction()
    {
		$User = Config::getObject('core.user.class');
		
		if($User->userName != null && $User->userName != 'guest'){
			$this->redirect(WebRouter::link("Admin/index"));
		} elseif (!empty($_POST)) {
            $login = $_POST['userName'];
            $pass = $_POST['password'];
            if($User->login($login, $pass)) {
                $this->redirect(WebRouter::link("Admin/index"));
            }
            else {
                $this->view->addVar('errorMessage', 'Неверное имя пользователя или пароль.');
                $this->redirect(WebRouter::link("Login/login&auth=deny"));
            }
        }
        else {
			$this->view->addVar('errorMessage', $this->errorMessage);
			$this->view->addVar('title', $this->title);
            $this->view->render('login/loginForm.php');
        }
    }


public function logoutAction() {
    // Очищаем сессию при выходе
    session_destroy();
    $this->redirect(WebRouter::link("Login/login"));
}

}
