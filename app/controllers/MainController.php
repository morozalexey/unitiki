<?php
namespace App\controllers;

use App\QueryBuilder;

use League\Plates\Engine;

use Delight\Auth\Auth;

use \Tamtamchik\SimpleFlash\Flash;

use SimpleMail;

use Respect\Validation\Validator as v;

use PDO;

class MainController {

    private $templates;
    private $db;
    private $auth;

    public function __construct(QueryBuilder $qb, Engine $engine, Auth $auth)
    {
        $this->templates = $engine;
        $this->auth = $auth;
        $this->db = $qb;
    } 

    public function registration(){   

        $email = v::email()->validate($_POST['email']);

            if ($email) {
        
            try {
            $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['name']
            /*    , function ($selector, $token) {
                SimpleMail::make()
                ->setTo($_POST['email'], $_POST['name'])
                ->setFrom('abuelofrio@yandex.ru', 'Alexey')
                ->setSubject('Registration')
                ->setMessage('https://www.your_cite_adress.com/verify_email?selector=' . \urlencode($selector) . '&token=' . \urlencode($token))
                ->send();
            }*/

            );
            /*
            flash()->success('We have signed you up! Please veryfy your email adress. Just check you mail and click the link. This will provide you acsees to our  site.');
                header("Location: /login_page");
                exit();
            */
            }
            catch (\Delight\Auth\InvalidEmailException $e) {
                flash()->error('Invalid email address');
            }
            catch (\Delight\Auth\InvalidPasswordException $e) {
                flash()->error('Invalid password');
            }
            catch (\Delight\Auth\UserAlreadyExistsException $e) {
                flash()->error('User already exists');
            }
            catch (\Delight\Auth\TooManyRequestsException $e) {
                flash()->error('Too many requests');
            }   
            
            header("Location: /login_page");
            exit();

        } else {

            flash()->error('Некорректный Email');
            header("Location: /registration_page");
            exit();
        } 

        $length = v::stringType()->length(5, null)->validate($_POST['password']);  

        if ($length) {
        
            try {
            $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['name']

            /*, function ($selector, $token) {
                SimpleMail::make()
                ->setTo($_POST['email'], $_POST['name'])
                ->setFrom('abuelofrio@yandex.ru', 'Alexey')
                ->setSubject('Registration')
                ->setMessage('https://www.your_cite_adress.com/verify_email?selector=' . \urlencode($selector) . '&token=' . \urlencode($token))
                ->send();
            }*/

            );
            /*flash()->success('We have signed you up! Please veryfy your email adress. Just check you mail and click the link. This will provide you acsees to our  site.');
                header("Location: /login_page");
                exit();*/
            }
            catch (\Delight\Auth\InvalidEmailException $e) {
                flash()->error('Invalid email address');
            }
            catch (\Delight\Auth\InvalidPasswordException $e) {
                flash()->error('Invalid password');
            }
            catch (\Delight\Auth\UserAlreadyExistsException $e) {
                flash()->error('User already exists');
            }
            catch (\Delight\Auth\TooManyRequestsException $e) {
                flash()->error('Too many requests');
            }   
            
            header("Location: /login_page");
            exit();

        } else {

            flash()->error('Пароль не должен быть короче 6 символов');
            header("Location: /registration_page");
            exit();
        }          

        $equals = v::equals($_POST['password'])->validate($_POST['password_confirmation']);

        if ($equals) {
        
            try {
            $userId = $this->auth->register($_POST['email'], $_POST['password'], $_POST['name']
                /*, function ($selector, $token) {
                SimpleMail::make()
                ->setTo($_POST['email'], $_POST['name'])
                ->setFrom('abuelofrio@yandex.ru', 'Alexey')
                ->setSubject('Registration')
                ->setMessage('https://www.your_cite_adress.com/verify_email?selector=' . \urlencode($selector) . '&token=' . \urlencode($token))
                ->send();
            }*/
            );
            /*flash()->success('We have signed you up! Please veryfy your email adress. Just check you mail and click the link. This will provide you acsees to our  site.');
                header("Location: /login_page");
                exit();*/
            }
            catch (\Delight\Auth\InvalidEmailException $e) {
                flash()->error('Invalid email address');
            }
            catch (\Delight\Auth\InvalidPasswordException $e) {
                flash()->error('Invalid password');
            }
            catch (\Delight\Auth\UserAlreadyExistsException $e) {
                flash()->error('User already exists');
            }
            catch (\Delight\Auth\TooManyRequestsException $e) {
                flash()->error('Too many requests');
            }   
            
            header("Location: /login_page");
            exit();

        } else {

            flash()->error('Пароли не совпадают');
            header("Location: /registration_page");
            exit();
        }

        
     
    }

    public function login(){  

        if ($_POST['remember'] == 1) {
            // keep logged in for one year
            $rememberDuration = (int) (60 * 60 * 24 * 365.25);
        }
        else {
            // do not keep logged in after session ends
            $rememberDuration = null;
        }

        try {
        $this->auth->login($_POST['email'], $_POST['password'], $rememberDuration);

        header("Location: /");
            exit();
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Wrong email address');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Wrong password');
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            flash()->error('Email not verified');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
        }  
        
        header("Location: /login_page");
        exit();       
    }


    public function logout()
    {
        $this->auth->logOut();
        header("Location: /login_page");
        exit();
    }


    public function email_verification(){

        try {
        $this->auth->confirmEmail($_GET['selector'], $_GET['token']);
            flash()->success('Email address has been verified');
        }
        catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            flash()->error('Invalid token');
        }
        catch (\Delight\Auth\TokenExpiredException $e) {
            flash()->error('Token expired');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error('Email address already exists');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error('Too many requests');
        }

        header("Location: /login_page");
        exit();
    }

        public function new_post()
    {
        $this->db->insert('posts', [            
            'text' => $_POST['text'],
            'title' => $_POST['title'],
            'category_id' => $_POST['category'],
            'hide' => 1,
            'dt_add' => date('Y-m-d')
        ]);
        //flash()->success('Post added');
        header("Location: /admin");
        exit();
    }


        public function change_post()
    {   
        $id = $_POST['id'];
        $this->db->update('posts', $id, ['title' => $_POST['title'], 'text' => $_POST['text']]);        
        header("Location: /admin");
        exit();
    }

        public function show()
    {
        $id = $_GET['id'];
        $this->db->update('posts',$id, ['hide' => 1]);
        header("Location: /admin");
        exit();
    }

    public function hide()
    {        
        $id = $_GET['id'];
        $this->db->update('posts', $id, ['hide' => 0]);
        header("Location: /admin");
        exit();
    }

    public function delete()
    {        
        $id = $_GET['id'];
        $this->db->delete('posts', $id);
        header("Location: /admin");
        exit();
    }

    public function change_password()
    {

        $equals = v::equals($_POST['new_password'])->validate($_POST['password_confirmation']);

        if ($equals) {

            try {
                $this->auth->changePassword($_POST['current_password'], $_POST['new_password']);
                flash()->success('Password has been changed');
            }
            catch (\Delight\Auth\NotLoggedInException $e) {
                flash()->error('Not logged in');
            }
            catch (\Delight\Auth\InvalidPasswordException $e) {
                flash()->error('Invalid password(s)');
            }
            catch (\Delight\Auth\TooManyRequestsException $e) {
                flash()->error('Too many requests');
            }
            header("Location: /profile");
            exit();  

        } else {

            flash()->error('Пароли не совпадают');
            header("Location: /profile");
            exit();
        }        
        
    }

    public function edit_profile(){

        $email = v::email()->validate($_POST['email']);

        if ($email) {
            
            if ($_POST['email']){
                $this->db->update('users', $_SESSION['auth_user_id'], ['email' => $_POST['email']]);
            }

            if (empty($_FILES['image']['tmp_name'])) {
                $this->db->update('users', $_SESSION['auth_user_id'], ['username' => $_POST['name']]);
            } else {
                $image = 'img/'.uniqid().'.jpg';
                move_uploaded_file($_FILES['image']['tmp_name'], $image);
                $this->db->update('users', $_SESSION['auth_user_id'], ['username' => $_POST['name'], 'avatar' => $image]);
            }
            
            flash()->success('Profile updated');
            header("Location: /profile");
            exit();    

        } else {

            flash()->error('Некорректный Email');
            header("Location: /profile");
            exit();
        } 
    }

}