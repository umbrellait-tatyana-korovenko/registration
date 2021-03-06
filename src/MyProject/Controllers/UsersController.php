<?php

namespace MyProject\Controllers;

use MyProject\Models\Users;
use MyProject\View\View;

class UsersController
{

    private $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public function signUp():void
    {
        if (!empty($_POST)) {
            $errorAboutValidationOfRegistration = \MyProject\Models\Users\User::registrationValidation($_POST);
            if ($errorAboutValidationOfRegistration === "Yes")
            {
                $user = \MyProject\Models\Users\User::signUp($_POST);
                $login = $_POST['nickname'] ?? '';
                $password = $_POST['password'] ?? '';
                setcookie('nickname', $login, 0, '/');
                setcookie('password', $password, 0, '/');
                //$this->view->renderTemplate('main.php');
                header("Location: /registration/public");
            } else
            {
                $this->view->renderHtml('signin.php', ['errors' => $errorAboutValidationOfRegistration]);
            }
        } else {
            $this->view->renderTemplate('signin.php');
        }
        if ($_COOKIE['nickname']) {
            header("Location: /registration/public");
        }
    }

    public function logIn():void
    {
        //$error = false;
        if (!empty($_POST)) {
            $login = $_POST['nickname'] ?? '';
            $password = $_POST['password'] ?? '';

            if (\MyProject\Models\Users\User::findOneUser($_POST)) {
                setcookie('nickname', $login, 0, '/');
                setcookie('password', $password, 0, '/');
                header('Location: /registration/public');
            } else {
                $error = 'Ошибка авторизации';
            }
        }
        $loginFromCookie = $_COOKIE['nickname'] ?? '';
        if ($loginFromCookie !== '') {
            header('Location: /registration/public');

        }
        $this->view->renderHtml('login.php', ['error' => $error]);
    }

    public function logOut():void
    {
        setcookie('nickname', '', -10, '/');
        setcookie('password', '', -10, '/');
        header('Location: /registration/public');
    }

    public function profileInfo():void
    {
        if (!$_COOKIE['nickname']) {
            header("Location: /registration/public");
        }
        \MyProject\Models\Users\User::profileInfo($_COOKIE['nickname'],$profileEmail,$profileData);
        if (!empty($_POST['deleteUser'])) {
            $sure = 'Are you sure you want to delete your account?';
            $this->view->renderHtml('profile.php', ['sureButton' => $sure, 'emailUser' => $profileEmail, 'dayOfUser' => ceil((time()-strtotime($profileData))/60/60/24)]);
        } elseif (!empty($_POST['yesDeleteUser'])) {
            \MyProject\Models\Users\User::deleteUser($_COOKIE['nickname']);
            setcookie('nickname', '', -10, '/');
            setcookie('password', '', -10, '/');
            header('Location: /registration/public');
        }else {
            //\MyProject\Models\Users\User::profileInfo($_COOKIE['nickname'],$profileEmail,$profileData);
            $this->view->renderHtml('profile.php', ['emailUser' => $profileEmail, 'dayOfUser' => ceil((time() - strtotime($profileData)) / 60 / 60 / 24)]);
        }
    }
}