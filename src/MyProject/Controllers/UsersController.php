<?php

namespace MyProject\Controllers;

use MyProject\Models\Users;

class UsersController
{
    public function signUp()
    {
        if (!empty($_POST)) {
            \MyProject\Models\Users\User::signUp($_POST);
        }

        // \MyProject\Models\Users\User::signUp($_POST);
        //var_dump(\MyProject\Models\Users\User::findAll()); //подумать, почему var_dump внутри функции не работает

        //$user_test_2 = new \MyProject\Models\Users\User('tuzik', 'tuzik@umbrellait.com', 'tuziktuziktuzik');
        //echo ($user_test_2 -> getNickname());
        //$user_test_2 -> save();
        include __DIR__ . '/../../../templates/signin.php';
    }
}