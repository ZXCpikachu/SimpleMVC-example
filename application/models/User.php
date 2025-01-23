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
   
    public function checkAuthData(string $login, string $pass): bool
    {
        $result = false;
        $User = new AllUsers();
        if ($login == Config::get('core.admin.username')) {
            if ($pass == Config::get('core.admin.password')) {
            $result = true;
        }
        } else {
            $authData = $User->getAuthData($login);
            if ($authData && $authData['password'] == $pass && $authData['active'] == 1){
                $result = true;
            }
        }
        return $result;
    }
}
