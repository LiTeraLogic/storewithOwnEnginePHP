<?php

function indexAction()
{
    $html =<<<php
    <form method="post" action="?p=auth&a=auth">
        <input name="login" type="text" placeholder="login">
        <input name="password" type="text" placeholder="password">
        <input type="submit">
    </form>
php;

    if (!empty($_SESSION['user'])) {
        //$html = '';
        $html = logInAction();
       /* $html .=<<<php
        <div>
            <a href="?p=order">Заказы</a>
        </div>
        
php;*/
    }


    return $html;
}

function authAction()
{
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        header('Location: ?p=auth');
        $_SESSION['msg'] = 'Что-то пошло не так';
        exit;
    }

    if (empty($_POST['login']) || empty($_POST['password']) ) {
        $_SESSION['msg'] = 'Нет полных данных';
        header('Location: ?p=auth');
        exit;
    }

    $login = $_POST['login'];
    $password = $_POST['password'];

    $sql = "
        SELECT 
            id, login, password, is_admin, address, tel 
        FROM 
            users 
        WHERE 
            login = '$login'
	";

    $result = mysqli_query(getConnect(), $sql);
    $user = mysqli_fetch_assoc($result);

//var_dump($user);
    $msg = 'Неверный логин или пароль';
    if (empty($user)) {
        $msg .= "Пользователя с таким именем не найдено. Зарегестрируйтесь!";
        header('Location: ?p=register');
        //$msg = 'im here';
        $_SESSION['msg'] = $msg;
        exit;
    }

//    password_hash("asdasd", PASSWORD_DEFAULT);
    if (password_verify($password, $user['password'])) {
    //if ($password == $user['password']) {
        $_SESSION['user']['login'] = $login;
        $_SESSION['user']['address'] = $user['address'];
        $_SESSION['user']['tel'] = $user['tel'];
        $_SESSION['user']['role'] = $user['is_admin'];
        header('Location: ?p=auth&a=logIn');
        exit;
    }
        $msg = "Неверный пароль";
        header('Location: ?p=auth');
        //$msg = 'im here';
        $_SESSION['msg'] = $msg;
        exit;

    /*$_SESSION['msg'] = $msg;

    header('Location: ?p=auth');
    exit;*/

}

function logInAction()
{
    $html = 'Что-то пошло не так. Попробуйте снова.';

    if (!empty($_SESSION['user'])) {
        $html = <<<php
        <div>
            <p>Добро пожаловать  {$_SESSION['user']['login']} </p>
            <a href="?p=auth&a=exit">Выход</a>
        </div>
        <div>
            <a href="?p=order">Заказы</a>
        </div>
php;
    }
    return $html;

}

function exitAction()
{
    session_destroy();
    header('Location: ?p=auth');
    exit;
}