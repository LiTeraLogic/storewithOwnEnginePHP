<?php
function addAction()
{
    $id = $_GET['id'];
    /*if (($_SESSION['basket'][$id] == '')){
        $count = 1;
        $nameGood = $_SESSION['good'][$id]['name_good'];
        $priceGood = $_SESSION['good'][$id]['price'];

        $_SESSION['basket'][$id]['count'] = $count;
        $_SESSION['basket'][$id]['name_good'] = $nameGood;
        $_SESSION['basket'][$id]['price'] = $priceGood;
        $_SESSION['basket'][$id]['cost'] = $priceGood;
   } else {*/
        $_SESSION['basket'][$id]['count']++;

        $cost = $_SESSION['basket'][$id]['price'] * $_SESSION['basket'][$id]['count'];

        $_SESSION['basket'][$id]['cost'] = $cost;
    //}



    //header("Location: {$_SERVER['HTTP_REFERER']}");
    header('Location: ?p=basket&a=basket');
    exit;

}

function deleteBasketAction()
{

    $id = $_GET['id'];
    if ($_SESSION['basket'][$id]['count'] <= 1){
        unset($_SESSION['basket'][$id]);
    } else{
        $_SESSION['basket'][$id]['count'] --;
    }

    header("Location: {$_SERVER['HTTP_REFERER']}");
}

function basketAction()
{
    $html = '<h2>Корзина</h2>';
    $price = 0;
    if (($_SESSION['user']['role']) == NULL) {
        $_SESSION['msg'] = 'Чтобы сделать заказ, авторизируйтесь.';
    }
    if (empty($_SESSION['basket'])) {
        $html .= "<p>Корзина пуста</p>";
        return $html;
    }
    $html .= getFormOrder();
    $html .= <<<php
        <h2>Заказ</h2>
php;
    $html .= '<script src="js/script.js"></script>';
    foreach ($_SESSION['basket'] as $key => $good) {
        $html .= <<<php
        <div>
        <a href="?p=good&a=one&id={$key}">{$good['name_good']}</a>
        <p>Цена: {$good['price']} рублей</p>
        <p>Кол-во: {$good['count']} шт</p>
        <a href="?p=basket&a=add&id={$key}">Добавить</a>
        <a href="?p=basket&a=deleteBasket&id={$key}">Удалить</a>
        <p>Стоимость: {$good['cost']} рублей</p>
         </div>
        
php;
        $price += $good['cost'];
    }
    $_SESSION['basketCount'] = $price;
    $html .= <<<php
        <div>
        <h2>Итого</h2>
        <p>Итоговая сумма: {$price} рублей</p>
         </div>
        
php;

    return $html;

}

function addAjaxAction()
{
    header('Access-Control-Request-Method: *');
    //header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

   if ($_SESSION['user']['role'] !== "0" || $_SESSION['user']['role'] !== "1") {
        echo json_encode([
            'success' => false,
            'error' => 'Прежде чем купить товар, зарегистрируйтесь.'
        ]);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hasAddGoodInBasket()) {
        echo json_encode([
            'success' => false,
            'error' => 'Товар не добавлен'
        ]);
        exit;
    }

    if ($_GET['basket'] === 'basket') {
        echo json_encode([
            'success' => true,
            'basketAction' => basketAction(),
            'basket' => $_SESSION['basket']
        ]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'countInBasket' => countInBasket(),
        'basketAction' => basketAction(),
        'basket' => $_SESSION['basket']
    ]);
    exit;
}


function hasAddGoodInBasket()
{
    if (empty($_GET['id'])) {
        return false;
    }

    $id = (int)$_GET['id'];
    $sql = "SELECT id, name_good, info, price FROM goods WHERE id = $id";
    $res = mysqli_query(getConnect(), $sql);
    $good = mysqli_fetch_assoc($res);

    if (empty($good)) {
        return false;
    }

    if (empty($_SESSION['basket'][$id])) {
        $_SESSION['basket'][$id] = [
            'count' => 1,
            'name' => $good['name_good'],
            'price' => $good['price'],
            'cost' => $good['price']
        ];
    } else {
        $_SESSION['basket'][$id]['count'] += 1;
        $_SESSION['basket'][$id]['cost'] =  $_SESSION['basket'][$id]['count'] *  $_SESSION['basket'][$id]['price'];
    }

    return true;
}


function getFormOrder()
{
    return <<<php
    <h3>Форма заказа</h3>
    <form method="post" action="?p=order&a=add">
        <input name="user_name" placeholder="user_name" value="{$_SESSION['user']['login']}">
        <input name="address" placeholder="address" value="{$_SESSION['user']['address']}"> 
        <input name="tel" placeholder="tel" value="{$_SESSION['user']['tel']}">
        <button>Заказать</button>
    </form>
php;
}

