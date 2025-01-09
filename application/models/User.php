<?php

namespace application\models;

use ItForFree\SimpleMVC\Config;
use ItForFree\SimpleMVC\MVC\Model;

class User extends \ItForFree\SimpleMVC\User
{
    public string $tableName = 'users';
    public string $orderBy = 'name ASC';

    protected function getRoleByUserName(string $userName): string {
        if( $userName == Config::get('core.admin.username') ){
            return $userName;
        } else {
            return "authorized";
        }
    }
    protected function checkAuthData(string $login, string $pass): bool
    {
        $result = false;

        if ($login == Config::get('core.admin.username')) {
            // Проверка для админа
            if ($pass == Config::get('core.admin.password')) {
                $result = true;
            }
        } else {
            // Проверка для обычного пользователя
            $sql = "SELECT pass, active FROM users WHERE name = :name;";
            $query = $this->pdo->prepare($sql);
            $query->bindValue(":name", $login, \PDO::PARAM_STR);
            $query->execute();
            $truePass = $query->fetch();
            if ($truePass && $truePass['pass'] == $pass && $truePass['active'] == 1) {
                $result = true; // Учет поля активного пользователя
            }
        }

        return $result;
    }
}
