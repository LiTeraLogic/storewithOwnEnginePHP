<?php

function indexAction()
{
    $html =<<<php
    <form method="post" action="?p=register&a=register">
        <input name="login" type="text" placeholder="login">
        <input name="password" type="text" placeholder="password">
        <input name="address" type="text" placeholder="address">
        <input name="tel" type="text" placeholder="telephone">
        <input type="submit">
    </form>
php;

    if (!empty($_SESSION['user'])) {
        $html = logInAction();
        $html .=<<<php
    
php;
    }

    return $html;
}

function registerAction()
{
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        header('Location: ?p=register');
        $_SESSION['msg'] = 'Что-то пошло не так';
        exit;
    }

    if (empty($_POST['login']) || empty($_POST['password']) ) {
        $_SESSION['msg'] = 'Нет полных данных';
        header('Location: ?p=register');
        exit;
    }

    $login = $_POST['login'];
    $password = $_POST['password'];
    $address = NULL;
    $tel = NULL;
    if (!empty($_POST['address'])) {
        $address = $_POST['address'];
    }
    if (!empty($_POST['tel'])) {
        $tel = $_POST['tel'];
    }

    $sql = "
        SELECT 
            id, login
        FROM 
            users 
        WHERE 
            login = '$login'
	";

    $result = mysqli_query(getConnect(), $sql);
    $user = mysqli_fetch_assoc($result);

    if (!empty($user)) {
        header('Location: ?p=register');
        $msg = 'Логин уже занят. Выберите новое имя.';
        $_SESSION['msg'] = $msg;
        exit;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "
            INSERT INTO 
                  `users` (login, password, is_admin, address, tel)
            VALUES 
                  ('$login', '$password', 0, '$address', '$tel');
    ";

    mysqli_query(getConnect(), $sql);

//    password_hash("asdasd", PASSWORD_DEFAULT);
    //if (password_verify($password, $user['password'])) {
    /*if ($password == $user['password']) {
        $_SESSION['user'] = $login;
        //$msg = 'Добро пожаловать';
        //$_SESSION['msg'] = $msg;
        header('Location: ?p=auth&a=logIn');
        exit;
    }*/

    $_SESSION['user']['login'] = $login;
    $_SESSION['user']['address'] = $address;
    $_SESSION['user']['tel'] = $tel;
    $_SESSION['user']['role'] = '0';
    header('Location: ?p=auth&a=logIn');
    exit;

}
