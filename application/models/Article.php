<?php

namespace application\models;
use ItForFree\SimpleMVC\Config as Config;
use ItForFree\SimpleMVC\MVC\Model;
class Article extends \ItForFree\SimpleMVC\MVC\Model
{
    public string $tableName = 'articles';
    public $publicationDate = null;
    public $title = null;
    public $subcategoryId = null;
    public $summary = null;
    public $content = null;
    public $active = null;
    public $articleId = null;
    public function storeFormValues ( $params ) {

      // Сохраняем все параметры
      $this->__construct( $params);

      // Разбираем и сохраняем дату публикации
      if ( isset($params['publicationDate']) ) {
        $publicationDate = explode ( '-', $params['publicationDate'] );

        if ( count($publicationDate) == 3 ) {
          list ( $y, $m, $d ) = $publicationDate;
          $this->publicationDate = mktime ( 0, 0, 0, $m, $d, $y );
        }
        if (isset($params['authors']) && is_array($params['authors'])){
            $this->authors = $params['authors'];
        }else {
            $this->authors= array();
        }
      }
    }
    
     public function getById(int $id, string $tableName = ''): ?Model {
        $tableName = !empty($tableName) ? $tableName : $this->tableName;
        $sql = "SELECT * FROM $tableName WHERE id = :id";
        $st = $this->pdo->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        if ($row) {
            return new static($row);
        }
        return null;
    }
    public function getList($numRows=1000000, $categoryId = null, $isSubcategory = null, $order = "publicationDate DESC") :array
    {
		if (!$isSubcategory) {
			$categoryClause = $categoryId ? "WHERE categoryId = $categoryId" : "";
		} else {
			$categoryClause = $categoryId ? "WHERE subcategoryId = $categoryId" : "";
		}
		
		if ($categoryClause) {
			$onlyActive = $numRows < 1000000 ? "AND active = 1" : "";
		} else {
			$onlyActive = $numRows < 1000000 ? "WHERE active = 1" : "";
		}
		
        $sql = "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(publicationDate) 
                AS publicationDate
                FROM articles $categoryClause $onlyActive
                ORDER BY  $order  LIMIT :numRows";
        
        $modelClassName = static::class;
       
        $st = $this->pdo->prepare($sql);
        $st->bindValue( ":numRows", $numRows, \PDO::PARAM_INT );
        $st->execute();
        $list = array();
        
        while ($row = $st->fetch()) {
            $example = new $modelClassName($row);
            $list[] = $example;
        }

        $sql = "SELECT FOUND_ROWS() AS totalRows"; //  получаем число выбранных строк
        $totalRows = $this->pdo->query($sql)->fetch();
        return (array ("results" => $list, "totalRows" => $totalRows[0]));
    }
    public function insert() {

        // Есть уже у объекта Article ID?
        if ( !is_null( $this->id ) ) trigger_error ( "Article::insert(): Attempt to insert an Article object that already has its ID property set (to $this->id).", E_USER_ERROR );

        // Вставляем статью
        $sql = "INSERT INTO articles ( publicationDate, categoryId,subcategoryId ,title, summary, content, active ) VALUES ( FROM_UNIXTIME(:publicationDate), :categoryId,:subcategoryId ,:title, :summary, :content, :active )";
        $st = $conn->prepare ( $sql );
        $st->bindValue( ":publicationDate", $this->publicationDate, PDO::PARAM_INT );
        $st->bindValue( ":categoryId", $this->categoryId, PDO::PARAM_INT );
        $st->bindValue( ":subcategoryId", $this->subcategoryId, PDO::PARAM_INT );
        $st->bindValue( ":title", $this->title, PDO::PARAM_STR );
        $st->bindValue( ":summary", $this->summary, PDO::PARAM_STR );
        $st->bindValue( ":content", $this->content, PDO::PARAM_STR );
        $st->bindValue( ":active", $this->activeArticle, PDO::PARAM_INT);
        $st->execute();
        $this->id = $conn->lastInsertId();
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute(); 
        foreach ($this->authors as $user) {
            $sql = "INSERT INTO users_article (user, article) VALUES (:user, :id)";
            $st = $conn->prepare($sql);
            $st->bindValue(":user", $user, PDO::PARAM_INT);
            $st->bindValue(":id", $this->id, PDO::PARAM_INT);

            if (!$st->execute()) {
                // Логирование ошибки или вывод сообщения об ошибке
                error_log("Error inserting into users_article: " . implode(", ", $st->errorInfo()));
            }
        }
        
        $conn = null;
    }

    /**
    * Обновляем текущий объект статьи в базе данных
    */
    public function update() 
    {        
      // Есть ли у объекта статьи ID?
      if (is_null($this->id)) trigger_error("Article::update(): Attempt to "
              . "update an Article object that does not have its ID property "
              . "set.", E_USER_ERROR);

      // Обновляем статью
      $sql = "UPDATE articles SET publicationDate=FROM_UNIXTIME(:publicationDate),"
              . " categoryId=:categoryId, subcategoryId=:subcategoryId,"
              . " title=:title, summary=:summary, content=:content,"
              . " active=:active WHERE id = :id";
      $st = $conn->prepare($sql);
      $st->bindValue(":publicationDate", $this->publicationDate, PDO::PARAM_INT);
      $st->bindValue(":categoryId", $this->categoryId, PDO::PARAM_INT);
      $st->bindValue(":subcategoryId", $this->subcategoryId, PDO::PARAM_INT);
      $st->bindValue(":title", $this->title, PDO::PARAM_STR);
      $st->bindValue(":summary", $this->summary, PDO::PARAM_STR);
      $st->bindValue(":content", $this->content, PDO::PARAM_STR);
      $st->bindValue(":id", $this->id, PDO::PARAM_INT);
      $st->bindValue(":active", $this->activeArticle, PDO::PARAM_INT);
      $st->execute();
      $sql = "DELETE FROM users_article WHERE articles = :id";
      $st = $conn->prepare($sql);
      $st->bindValue(":id", $this->id, PDO::PARAM_INT);
      $st->execute();
      foreach ($this->authors as $author) {
        $sql = "INSERT INTO users_article (user, articles) VALUES (:user, :id)";
        $st = $conn->prepare($sql);
        $st->bindValue(":user", $author, PDO::PARAM_INT);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
    }
      $conn = null;
    }


    /**
    * Удаляем текущий объект статьи из базы данных
    */
    public function delete() : void {

      // Есть ли у объекта статьи ID?
      if ( is_null( $this->id ) ) trigger_error ( "Article::delete(): Attempt to delete an Article object that does not have its ID property set.", E_USER_ERROR );

      // Удаляем статью
      $st = $conn->prepare ( "DELETE FROM articles WHERE id = :id LIMIT 1" );
      $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
      $st->execute();
      $st = $conn->prepare("DELETE FROM users_article WHERE article = :id");
      $st->bindValue(":id", $this->id, PDO::PARAM_INT);
      $st->execute();
    }
}