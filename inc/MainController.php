<?php

namespace Inc;

class MainController
{
    private $view;
    private $api;
    private $errors = [];

    function __construct()
    {
        $this->view = new View();
        $this->api = new Api();
    }

    public function generateUserId()
    {
        if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            $this->errors[] = 'Email is not valid!';
        if(empty($_POST['code']) || !preg_match('/\d{1,3}/',$_POST['code']))
            $this->errors[] = 'Country code is not valid! (1-3 numbers)';
        if(empty($_POST['phone']) || !preg_match('/[\d\- ]{6,11}/',$_POST['phone']))
            $this->errors[] = 'Phone number is not valid!';
        if($this->errors)
            return false;

        $user_id = $this->api->registerUser(htmlspecialchars($_POST['email']), htmlspecialchars($_POST['code']), htmlspecialchars($_POST['phone']));

        if($this->errors = $this->api->getErrors()) {
            return false;
        }

        return $user_id;
    }

    public function verifyUser()
    {
        if(empty($_POST['token']))
            $this->errors[] = 'Token is required - something going wrong!';
        if(empty($_POST['user_id']))
            $this->errors[] = 'User Id is required - something going wrong!';

        if (!$this->errors) {
            $this->api->verifyUserViaSms(htmlspecialchars($_POST['user_id']), htmlspecialchars($_POST['token']));
        }

        if($this->errors = $this->api->getErrors()) {
            return false;
        }

        return true;
    }

    public function actionFormAddUsers()
    {
        $this->view->setErrors($this->errors);
        $this->view->render('forms/form_add_users.php' );
    }

    public function successPage()
    {
        $this->view->render('success.php' );
    }

    public function actionFormTokens($userId = null)
    {
        $this->view->setErrors($this->errors);
        $vars = [
            'userId' => $userId ? :htmlspecialchars($_POST['user_id']),
        ];
        $this->view->render('forms/form_tokens.php', $vars);
    }

    public function getErrors(){
        return $this->errors;
    }
}