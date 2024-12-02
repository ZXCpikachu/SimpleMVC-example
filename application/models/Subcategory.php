<?php
namespace application\models;
use ItForFree\SimpleMVC\Config as Config;
use ItForFree\SimpleMVC\Application;
use ItForFree\SimpleMVC\MVC\Model;
use ItForFree\rusphp\Log\SimpleEchoLog;
class Subcategory extends \ItForFree\SimpleMVC\MVC\Model
{
    public string $tableName = 'subcategories';
    public ?int $id = null;
    public $name = null;
    public $cat_id = null;
    public function storeFormValues($params){
		$this->__construct( $params );
	}
    public function getList($numRows=1000000, $categoryId=null, $order="name ASC") : array
	{
		$categoryClause = $categoryId ? "WHERE cat_id = $categoryId" : "";
		
		  $sql = "SELECT * FROM $this->tableName $categoryClause ORDER BY $order LIMIT :numRows";
		
		$st= $this->pdo->prepare($sql);
		$st->bindValue(":numRows", $numRows, \PDO::PARAM_INT );
		$st->execute();
		$list = array();
		
		while( $row = $st->fetch() ){
			$subcategory = new Subcategory($row);
			$list[] = $subcategory;
		}
		
		$sql = "SELECT FOUND_ROWS() AS totalRows";
		$totalRows = $this->pdo->query($sql)->fetch();
		$conn = null;
		return (array("results" => $list, "totalRows" => $totalRows[0] ) );
	}
    public function getCategIdByName($name){
        $sql = "SELECT id FROM categories WHERE name = :name ";
        $st = $this->pdo->prepare($sql);
        $st->bindValue(":name", $name, PDO::PARAM_STR);
	$st->execute();
	$row = $st->fetch();
	$conn = null;
	if($row){
                    return $row[0];
		}
    }
     public function isSubcategoryExist($name)
    {
        $sql = "SELECT name FROM $this->tableName WHERE name = :name";
        $st = $this->pdo->prepare($sql);
        $st->bindValue(":name", $name, \PDO::PARAM_STR);
        $st->execute();
        return $st->fetch() !== false;
    }
    public function insert(){
		// Проверяем есть ли уже у обьекта Subcategory ID ?
		if ( !is_null( $this->id ) ) trigger_error ( "Subcategory::insert(): "
				. "Attempt to insert a Subcategory object that already has its "
				. "ID property set (to $this->id).", E_USER_ERROR );
		//Вставляем субкатегорию
		$sql = "INSERT INTO $this->tableName(name, cat_id) VALUES(:name, :cat_id)";
		$st = $this->pdo->prepare($sql);
		$st->bindValue(":name", $this->name, \PDO::PARAM_STR );
		$st->bindValue(":cat_id", $this->cat_id, \PDO::PARAM_INT );
		$st->execute();
		$this->id = $this->pdo->lastInsertId();
	}
    public function update(){
		// Проверяем есть ли уже у обьекта Subcategory ID ?
		if ( is_null( $this->id ) ) trigger_error ( "Subcategory::insert(): "
				. "Attempt to insert a Subcategory object that does not have its "
				. "ID property set (to $this->id).", E_USER_ERROR );
		$sql = "UPDATE $this->tableName SET name=:name, cat_id=:cat_id WHERE id=:id";
		$st = $this->pdo->prepare($sql);
		$st->bindValue(":name", $this->name, \PDO::PARAM_STR);
		$st->bindValue(":cat_id", $this->cat_id, \PDO::PARAM_INT);
		$st->bindValue(":id", $this->id, \PDO::PARAM_INT);
		$st->execute();
        }
    public function delete() :void {
		// У объекта Subcategory  есть ID?
      if ( is_null( $this->id ) ) trigger_error ( "Subcategory::delete(): "
			  . "Attempt to delete a Subcategory object that does not have its "
			  . "ID property set.", E_USER_ERROR );
      $st = $this->pdo->prepare ( "DELETE FROM $this->tableName WHERE id = :id LIMIT 1" );
      $st->bindValue( ":id", $this->id, \PDO::PARAM_INT );
      $st->execute();
		
	}
}
