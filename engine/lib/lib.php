<?php

/**
 * @return false|mysqli
 */
function getConnect()
{
    static $link;
    if (empty($link)) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $link = mysqli_connect('localhost', 'root', '', 'shop', '3307');
    }
    return $link;
}

/**
 * @param $str
 * @return string
 */
function clearStr($str)
{
    $str = trim($str);
    $str = strip_tags($str);
    $str = mysqli_real_escape_string(getConnect(), $str);

    return $str;
}

function countInBasket()
{
    if (empty($_SESSION['basket'])) {
        return 0;
    }

    return count($_SESSION['basket']);
}

function isAdmin()
{
    if (empty($_SESSION['user']['role'])) {
        header('Location: /');
        exit;
    }
}

function getMenu(){
    $countInBasket = countInBasket();

    $html = <<<php
        <li><a href="/">Главная</a></li>
        <li><a href="?p=good">Товары</a></li>
        <li><a href="?p=basket&a=basket">Корзина </a><span id="countInBasket">{$countInBasket}</span></li>
        <li><a href="?p=index&a=about">О нас</a></li>  
        
php;
    //<li><a href="?p=auth">Авторизация</a></li>
    if ($_SESSION['user']['role'] == '1'){
        $html .= <<<php
            <li><a href="?p=order">Все заказы</a></li>
            <li><a href="?p=good&a=add">Добавить товар</a></li>
php;
    }

    //if (!empty($_SESSION['user']['role']) and !empty($_SESSION['user'])){
    if ($_SESSION['user']['role'] != NULL){
        $html .= <<<php
            <li><a href="?p=auth">Личный кабинет</a></li>
php;
    }

    if (empty($_SESSION['user'])){
        $html .= <<<php
            <li><a href="?p=auth">Авторизация</a></li>
php;
    }

    return $html;
}

function getStatus()
{
    static $status = [
                        '1' => 'в рассмотрении',
                        '2' => 'доставка',
                        '3' => 'оплачен',
                        '4' => 'получен',
    ] ;
    return $status;
}
