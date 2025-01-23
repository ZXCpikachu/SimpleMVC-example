<?php

namespace application\models;
use ItForFree\SimpleMVC\Config;
use ItForFree\SimpleMVC\MVC\Model;
class AllUsers extends \ItForFree\SimpleMVC\MVC\Model
{
	/**
     * Имя таблицы пользователей
     */
    public string $tableName = 'users';
	
	/**
     * @var string Критерий сортировки строк таблицы
     */
    public string $orderBy = 'name ASC';
	
	//Свойства
	/**
    * @var int ID пользователя из базы данных
    */
    public ?int $id = null;
	
	/**
    * @var string Логин пользователя
    */
    public $login = null;
	
	/**
    * @var string пароль пользователя
    */
    public $password = null;
	
	/**
    * @var bool индикатор, показывающий активен пользователь или нет 
    */
    public $active = null;

    public function isUserExist($login){
        $sql = "SELECT name FROM users WHERE login = :login";
        $st = $this->pdo->prepare($sql);
        $st->bindValue(":login",$login, \PDO::PARAM_STR);
        $st->execute();
        if ($st->fetch()[0]){
            return true;
        }else {
            return false;
        }
    }
        public function storeFormValues($params){
                    $this->__construct( $params );
            }
            
        public function getList($numRows=1000000):array
            {
                $sql = "SELECT  * FROM users";
                $st = $this->pdo->query($sql);
                $list = array ();
                while ($row = $st->fetch()){
                    $user = new AllUsers($row);
                    $list[] = $user;
                }
                $sql = "SELECT FOUND_ROWS() AS totalRows";
                $totalRows = $this->pdo->query($sql)->fetch();
                return (array(
                    "results" =>$list,
                    "totalRows" => $totalRows[0]
                )
                );
        }   
        public function insert(){
            $sql = "INSERT INTO users(login, password, active) VALUES(:login, :password, :active)";
            $st = $this->pdo->prepare($sql);
            $st->bindValue(":login", $this->login, \PDO::PARAM_STR );
            $st->bindValue(":password", $this->password, \PDO::PARAM_STR );
            $st->bindValue(":active", $this->active, \PDO::PARAM_INT );
            $st->execute();
            $this->id = $this->pdo->lastInsertId();
	}
        public function update($user) {
            $sql = "UPDATE $this->tableName SET login=:login, password=:password, active=:active WHERE id = :id";
            $st = $this->pdo->prepare( $sql );
            $st->bindValue( ":login", $user->login,\PDO::PARAM_STR );
            $st->bindValue( ":password", $user->password, \PDO::PARAM_STR );
	    $st->bindValue( ":active", $user->active, \PDO::PARAM_INT );
            $st->bindValue( ":id", $user->id, \PDO::PARAM_INT );
            $st->execute();
        }
         public function getById(int $id, string $tableName = ''): ?Model
    {  
        $tableName = !empty($tableName) ? $tableName : $this->tableName;
        
        $sql = "SELECT * FROM $tableName where id = :id";      
        $modelClassName = static::class;
        
        $st = $this->pdo->prepare($sql); 
        
        $st->bindValue(":id", $id, \PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        
        if ($row) { 
            return new $modelClassName( $row );
        } else {
            return null;
        }
    }
        public function delete():void{
            $st = $this->pdo->prepare("DELETE FROM users WHERE id = :id LIMIT 1");
            $st->bindValue(":id",$this->id,\PDO::PARAM_STR);
            $st->execute();
            $st = $this->pdo-> prepare("DELETE FROM users_article WHERE user = :id");
            $st->bindValue(":id", $this->id,\PDO::PARAM_INT);
            $st->execute();
        }
        
        public function getAuthData($login): ?array {
        $sql = "SELECT password,active FROM users WHERE login = :login";
        $st = $this->pdo->prepare($sql);
        $st->bindValue(":login", $login, \PDO::PARAM_STR);
        $st->execute();
        return $st->fetch(); 
    }

        public function checkAuthData($login, $password): bool {
        $sql = "SELECT password FROM users WHERE login = :login";
        $st = $this->pdo->prepare($sql);
        $st->bindValue(":login", $login, \PDO::PARAM_STR);
        $st->execute();
        $authData = $st->fetch();
        if ($authData && password_verify($password, $authData['pass'])) {
            return true;
        } else {
            return false;
        }
    }

    
    
    
}


