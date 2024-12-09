<?php

namespace application\controllers;
use ItForFree\SimpleMVC\Config;
use application\models\Article;
use application\models\AllUsers;
use application\models\Category;
use application\models\Connection;
use application\models\Subcategory;
class HomepageController extends \ItForFree\SimpleMVC\MVC\Controller
{
    public string $layoutPath = 'main.php';
    public $title = 'CMS на PHP';
    public $articlesData = array();
    public $results = array();
    public $Article = null;
    public $Category = null;
    public $Subcategory = null;
    public $Connection = null;
    public $Users = null;
    
    
    /*
     * Инициализация всех сущностей
     */
    protected function initModelObjects(){
        $this->Article = new Article();
        $this->Category = new Category();
        $this->Subcategory = new Subcategory();
        $this->Connection = new Connection();
        $this->Users = new AllUsers();
    }
    protected  function getArticles()
    {
        $this->results['articles'] = $this->articlesData['results'];
        $this->results['totalRows'] = $this->articlesData['totalRows'];
        $this->articlesData = $this->Subcategory->getList();
        $this->results['subcategories'] = array();
        foreach ( $this->articlesData['results'] as $subcategory ) { 
			$this->results['subcategories'][$subcategory->id] = $subcategory;
			$this->results['categories'][$subcategory->id] = $this->Category->
					getById($subcategory->cat_id);
		}
    }
    public function indexAction()
    {
        $this->initModelObjects();
        $this->articlesData = $this->Article->getList(Config::get('core.homepageNumArticles'));
        $this->getArticles();
        foreach ($this->results['articles'] as $article)
        {
            $article->content = substr($article->content,0,100) . ' ...';
        }
        $this->view->addVar('title',$this->title);
        $this->view->addVar('results', $this->results);
        $this->view->render('homepage/homepage.php');
    }
    
    public function viewArticleAction(){
        $this->initModelObjects();
        $this->articlesData['id'] = $_GET['articleId'];
        $SingleArticle = $this->Article->getById($this->articlesData['id']);
        $this->title = $SingleArticle->title . ' | ' . $this->title;
        $this->results['article']['id'] = $SingleArticle->id;
        $this->results['article']['title'] = $SingleArticle->title;
        $this->results['article']['publicationDate'] = $SingleArticle->publicationDate;
        $this->results['article']['subcategoryId'] = $SingleArticle->subcategoryId;
        $this->results['article']['summary'] = $SingleArticle->summary;
        $this->results['article']['content'] = $SingleArticle->content;
        $this->results['article']['active'] = $SingleArticle->active;
        $this->results['article']['subcategory'] = $this->Subcategory->getById(
                $this->results['article']['subcategoryId']);
        $connections = $this->Connection->getById($this->results['article']['id']);
        $connectionsCount = count($connections);
        
        foreach ($connections as $connection)
        {
            $userId = $connection->user_id;
            $this->results['authors'][] = $this->User->getById($userId)->name;
        }
        $this->view->addVar('article', $SingleArticle);
        $this->view->addVar('results', $this->results);
        $this->view->addVar('title', $this->title);
	$this->view->render('homepage/singleArticle.php');
        
    }
    
    public function archiveAction(){
		$this->initModelObjects();
		$this->articlesData = $this->Article->getList(100000);
		$this->getArticles();
		$this->results['category'] = 0;
		$this->results['subcategory'] = 0;
		$this->results['pageHeading'] = "Article Archive";
		$this->title = $this->results['pageHeading'] . " | Widget News";
		
		$this->view->addVar('title', $this->title);
		$this->view->addVar('results', $this->results);
		/*Передаем также объект категории т.к. его методы унаследованы от 
		 * родительского класса model и не являются статическими*/
		$this->view->addVar('Category', $this->Category);
		$this->view->render('homepage/archive.php');
	}
    public function archiveCatAction() {
        $this->initModelObjects();
        $subcategoryId = ( isset( $_GET['subcategoryId'] ) && 
				$_GET['subcategoryId'] ) ? (int)$_GET['subcategoryId'] : null;
        $this->results['subcategory'] = $this->Subcategory->getById($subcategoryId);
        $this->results['category'] = $this->Category->getById(
                $this->results['subcategory']->cat_id);
        $data = $this->Subcategory->getList(100000,$this->results['subcategory']->cat_id);
        $articleArr = array();
        foreach($data['results'] as $subcategory){
			$articleArr[] = $this->Article->getList(100000, $subcategory->id, true);
	}
        $this->results['articles'] = array();
	$this->results['totalRows'] = 0; 
        for( $i = 0; $i < count($articleArr); $i++){
			$this->results['articles'] = array_merge($this->results['articles'], 
					$articleArr[$i]['results']);
			$this->results['totalRows'] = $this->results['totalRows'] + 
					$articleArr[$i]['totalRows'];
		}
		if($this->results['category']){
			$this->results['pageHeading'] = $this->results['category']->name;
			$this->title = $this->results['category']->name;
		}else{
			$this->results['pageHeading'] = "Article Archive";
		}	
	$this->view->addVar('title', $this->title);
	$this->view->addVar('results', $this->results);
	$this->view->addVar('Category', $this->Category);
	$this->view->render('homepage/archive.php');
    }
}
