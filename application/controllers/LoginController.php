<?php
namespace application\controllers;

use ItForFree\SimpleMVC\Config;
use ItForFree\SimpleMVC\Router\WebRouter; // Подключаем WebRouter

class LoginController extends \ItForFree\SimpleMVC\MVC\Controller
{
    public string $layoutPath = 'main.php';
    public $title = 'Admin Login';
    public $errorMessage = 'Неправильный логин или пароль';

   public function loginAction() {
    $User = Config::getObject('core.user.class');
    
    // Проверка, если пользователь уже авторизован
    if (isset($_SESSION['user']) && $_SESSION['user'] != 'guest') {
        $this->redirect(WebRouter::link("Admin/index"));
    }

    if (!empty($_POST)) {
        $login = $_POST['login'];
        $pass = $_POST['password'];

        // Пытаемся выполнить вход с использованием checkAuthData
        if ($User->checkAuthData($login, $pass)) {
            // Успешный вход, сохраняем информацию в сессии
            $_SESSION['user'] = $login;  // Можно сохранить логин или другие данные пользователя
            $this->redirect(WebRouter::link("Admin/index"));
        } else {
            // Неудачный вход, передаем сообщение в сессию
            $_SESSION['errorMessage'] = $this->errorMessage;
            $this->redirect(WebRouter::link("Login/login")); // Перенаправление на страницу входа
        }
    } else {
        // Передаем ошибки и заголовок в представление
        if (isset($_SESSION['errorMessage'])) {
            $this->view->addVar('errorMessage', $_SESSION['errorMessage']);
            unset($_SESSION['errorMessage']);
        }
        
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
