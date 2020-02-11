<?php

namespace App\controllers;

use App\QueryBuilder;

use League\Plates\Engine;

use Delight\Auth\Auth;

use \Tamtamchik\SimpleFlash\Flash;

use PDO;

class PagesController {

    private $templates;
    private $db;
    private $auth;

    public function __construct(QueryBuilder $qb, Engine $engine, Auth $auth)
    {
        $this->templates = $engine;
        $this->db = $qb;
    }

    public function index(){      
        
        $posts = $this->db->getAllPosts('posts');       
        
        echo $this->templates->render('homepage', ['posts' => $posts]);       
    }

    public function category(){  

        $category_id = $_GET['category_id'];

        //$posts = $this->db->getPostsByCategory('posts', $category_id);

        $sql = "SELECT posts.dt_add, posts.title, posts.text, posts.category_id, categories.category_name FROM posts, categories WHERE category_id = :category_id AND hide = 1 ORDER BY posts.dt_add DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);       
        
        echo $this->templates->render('homepage', ['posts' => $posts]);       
    }
    
    public function post_page(){
        
        $id = $_GET['id'];   
          
        $posts = $this->db->getOne('posts', $id);     
        
        echo $this->templates->render('post_page', ['posts' => $posts]);       
    }
    

    public function registration_page(){

        echo $this->templates->render('registration');
    }

    public function login_page(){

        echo $this->templates->render('login');
    } 

    public function admin(){

        $posts = $this->db->getAll('posts');
        $categories = $this->db->getAll('categories');

        echo $this->templates->render('admin', ['posts' => $posts, 'categories' => $categories]);
    } 
}