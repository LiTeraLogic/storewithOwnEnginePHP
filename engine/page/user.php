<?php

function indexAction()
{
    return allAction();
}

function oneAction()
{
    return 'Пользователь ';
}

function allAction()
{
    $sql = "SELECT id, login, password, is_admin FROM users";
    $res = mysqli_query(getConnect(), $sql);

    $html = '';
    while ($row = mysqli_fetch_assoc($res)) {
        $html .= <<<php
        <a href="?page=3&id={$row['id']}">{$row['login']}</a><br>
        <a href="">Купить</a>
         
        <hr>
php;

    }

    return $html;
}