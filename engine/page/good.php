<?php

function indexAction()
{
    return allAction();
}

function oneAction()
{
    $id = $_GET['id'];
    $sql = "SELECT id, name_good, price, info FROM goods WHERE id='$id'";
    $res = mysqli_query(getConnect(), $sql);
    $html = '';
    $html .= '<script src="js/script.js"></script>';
    while ($row = mysqli_fetch_assoc($res)) {
        $html .= <<<php
            <div style="overflow: hidden; display:inline-block; width:200px">
            <a href="?page=3&a=one&id={$row['id']}">{$row['name_good']}</a><br>
            <p>{$row['info']}</p>
            <p>Цена: {$row['price']} рублей</p>
            <span style="cursor: pointer" onclick="addGoods({$row['id']})">Добавить в корзину</span><br>
             </div>
            
php;
    }
    return $html;
}


function allAction()
{
    $sql = "SELECT id, name_good, price, info FROM goods";
    $res = mysqli_query(getConnect(), $sql);
    $html = '<h2>Каталог</h2>';
    $html .= '<script src="js/script.js"></script>';
    while ($row = mysqli_fetch_assoc($res)) {
        $_SESSION['good'][$row['id']] = ['name_good' => $row['name_good'],
                                        'price' => $row['price'],
                                        'info' => $row['info']
                                        ];
            $html .= <<<php
        <div style="overflow: hidden; display:inline-block; width:200px">
        <a href="?p=good&a=one&id={$row['id']}">{$row['name_good']}</a><br>
        <p>{$row['info']}</p>
        <p>Цена: {$row['price']} рублей</p>
        <span style="cursor: pointer" onclick="addGoods({$row['id']})">Добавить в корзину</span><br>
        <!--<a href="?p=basket&a=addBasket&id={$row['id']}">Купить</a>-->
         </div>
        
php;

    }
    header('Access-Control-Allow-Origin: *');
    return $html;
}

function addAction()
{
    $html =<<<php
    <form method="post" action="?p=good&a=add">
        <input name="newGood[nameGood]" type="text" placeholder="Название товара"><Br>
        <input name="newGood[price]" type="text" placeholder="Цена"><Br>
        <textarea name="newGood[info]" placeholder="Описание" id="check1"></textarea><Br>
        <input type="submit">
    </form>
php;
    $msg = "";
    if(!empty($_POST)){
        $nameGood = $_POST['newGood']['nameGood'];
        $price = $_POST['newGood']['price'];
        $info = $_POST['newGood']['info'];

        $sql = "INSERT INTO goods (name_good , price, info) VALUES ('$nameGood', '$price', '$info')";
        mysqli_query(getConnect(), $sql);
        $msg = "Товар добавлен";
    }

    $_SESSION['msg'] = $msg;

    return $html;



}