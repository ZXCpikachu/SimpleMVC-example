<?php
namespace application\controllers;
use ItForFree\SimpleMVC\Config;
use ItForFree\SimpleMVC\Url;
use application\models\Article;
use application\models\AllUsers;
class AdminController extends \ItForFree\SimpleMVC\MVC\Controller
{
    public $articlesData = array();
    public $results = array();
    public $title = 'CMS на PHP';
    public $Article = null;
    public $Category = null;
    public $Subcategory = null;
    public $Users = null;
    public $Connection = null;
    protected function initModelObjects(){
		$this->Article = new Article;
		$this->Category = new Category;
		$this->Subcategory = new Subcategory;
		$this->Users = new AllUsers;
		$this->Connection = new Connection;
	}
    protected function getArticles(){
        $this->articlesData = $this->Article->getList();
        $this->results['articles'] = $this->articlesData['results'];
        $this->results['totalRows'] = $this->artcilesData['totalRows'];
        $this->articlesData = $this->Subcategory->getList();
        $this->results['subcategories'] = array();
        foreach($this->results['subcategories'] as $subcategory){
            $this->results['subcategories'][$subcategory->id] = $subcategory;
            $this->results['categories'] [$subcategory->id] = $this->Category->getById($subcategory->categoryId);
        }
    }
    public function indexAction(){
        $this->initModelObjects();
        $this->getArticles();
        $this->view->addVar('title', $this->title);
        $this->view->addVar('results', $this->results);
        $this->view->render('admin/admin.php');
    }
    public function viewArticleAction(){
		$this->articlesData['id'] = $_GET['articleId'];
		$Article = new Article();
		$SingleArticle = $Article->getById($this->articlesData['id']);
                $this->title = $SingleArticle->title . ' | ' . $this->title;
		$this->results['article']['id'] = $SingleArticle->id;
		$this->results['article']['title'] = $SingleArticle->title;
		$this->results['article']['publicationDate'] = $SingleArticle->publicationDate;
		$this->results['article']['subcategoryId'] = $SingleArticle->subcategoryId;
		$this->results['article']['summary'] = $SingleArticle->summary;
		$this->results['article']['content'] = $SingleArticle->content;
		$this->results['article']['active'] = $SingleArticle->active;
		$this->view->addVar('results', $this->results);
                $this->view->addVar('title', $this->title);
		$this->view->render('singleArticle/singleArticle.php');
		
	}
    public function listCategoriesAction(){
        $this->initModelObjects();
        $data = $this->Category->getList();
        $this->results['categories'] = $data['results'];
        $this->results['totalRows'] = $data['totalRows'];
        $this->results['pageTitle'] = "List of categories";
        $this->title = $this->results['pageTitle'];
        $this->view->addVar('title', $this->title);
        if (isset($_GET['error'])) {
			if ($_GET['error'] == "categoryNotFound")
				$this->results['errorMessage'] = "Error: Category not found.";
			if ($_GET['error'] == "categoryContainsArticles")
				$this->results['errorMessage'] = "Error: Category contains subcategories. "
					. "Delete the subcategories, or assign them to another category, "
					. "before deleting this category.";
		}

		if (isset($_GET['status'])) {
			if ($_GET['status'] == "changesSaved")
				$this->results['statusMessage'] = "Your changes have been saved.";
			if ($_GET['status'] == "categoryDeleted")
				$this->results['statusMessage'] = "Category deleted.";
		}
        $this->view->addVar('results',$this->results);
        $this->view->render('admin/listCategories.php');
    }
    public function listSubcategoriesAction(){
        $this->initModelObjects();
        $data = $this->Subcategory->getList();
        $this->results['subcategories'] = $data['results'];
        $this->results['totalRows'] = $data['totalRows'];
        $this->results['pageTitle'] = "List of subcategories";
        $this->title = $this->results['pageTitle'];
        $this->view->addVar('title',$this->title);
        foreach ($this->results['subcategories'] as $subcategory) {
			$category = $this->Category->getById($subcategory->categoryId);
			$subcategory->cat_name = $category->name;
		}
        if (isset($_GET['error'])){
            if ($_GET['error'] == "subcategoryNotFound")
                $this->results['errorMessage'] = "Error: Subcategory not found.";
            if ($_GET['error'] == "subcategoryContainsArticles")
                $this->results['errorMEssage'] = "Error: Subcategory contains articles";
        }
        if (isset($_GET['status'])){
            if ($_GET['status'] == "changesSaved")
                $this->results['statusMessage'] = "Your changes have been saved.";
            if ($_GET['status'] == "subcategoryDeleted")
                $this->results['statusMessage'] = "Subcategory deleted";
        }
        $this->view->addVar('results', $this->results);
        $this->view->render('admin/listSubcategory.php');
    }
    public function listUsersAction(){
        $this->initModelObjects();
        $data = $this->Users->getList();
        $this->results['users'] = $data['results'];
        $this->results['totalRows'] = $data['totalRows'];
        $this->results['pageTitle'] = "User list";
        $this-> title = $this->results['pageTitle'];
        $this->view->addVar('title',$this->title);
        if (isset($_GET['error'])){
            if ($_GET['error'] == "userNotFound")
                $this->results['errorMessage'] = "Error: User not found.";
            if ($_GET['error'] == "userExist")
                $this->results['errorMessage'] = "Error: User with such name is alredy exist.";
        }
        if (isset($_GET['status'])){
            if ($_GET['status'] == "changesSaved")
                $this->results['statusMessage'] = "Your changes have been saved.";
            if ($_GET['status'] == "subcategoryDeleted")
                $this->results['statusMessage'] = "Subcategory deleted";
        }
        $this->view->addVar('results', $this->results);
	$this->view->render('admin/listUsers.php');
    }
}


