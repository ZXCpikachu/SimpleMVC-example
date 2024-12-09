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
            $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM users";
            $st = $this->pdo->query($sql);
            $list = array ();
            while ($row = $st->fetch()){
                $user = new User($row);
                $list[] = $user;
            }
            $sql = "SELECT FOUND_ROWS() AS totalRows";
            $totalRows = $this->pdo->query($sql)->fetch();
            $conn = null;
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
        public function update() {
            $sql = "UPDATE users SET login=:login, password=:password, active=:active WHERE id = :id";
            $st = $this->pdo->prepare( $sql );
            $st->bindValue( ":login", $this->login,\PDO::PARAM_STR );
            $st->bindValue( ":password", $this->password, \PDO::PARAM_STR );
	    $st->bindValue( ":active", $this->active, \PDO::PARAM_INT );
            $st->bindValue( ":id", $this->userId, \PDO::PARAM_INT );
            $st->execute();
        }
         public function getById(int $id, string $tableName = ''): ?Model{
            $sql = "SELECT * FROM users WHERE id = :id ";
            $st = $this->pdo->prepare($sql);
            $st->bindValue(":id",$id,PDO::PARAM_INT);
            $st->execute();
            $row = $st->fetch();
            if ($row){
                return new User($row);
            }
        }
        public function delete():void{
            $st = $this->pdo->prepare("DELETE FROM users WHERE login = :login LIMIT 1");
            $st->bindValue(":login",$this->login,PDO::PARAM_STR);
            $st->execute();
//            $st = $this->pdo-> prepare("DELETE FROM users_aritcles WHERE user = :id");
//            $st->bindValue(":id", $this->$id,PDO::PARAM_INT);
//            $st->execute();
        }
    
    
    
}


