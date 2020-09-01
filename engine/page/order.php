<?php

function indexAction()
{
    $sql = "SELECT id, user_name, address, price, tel, order_data, status  FROM orders";

    if ($_SESSION['user']['role'] == '0'){
        $user_name = $_SESSION['user']['login'];
        $sql = "SELECT id, user_name, address, price, tel, order_data, status  FROM orders WHERE user_name = '$user_name'";
    }

    $res = mysqli_query(getConnect(), $sql);
    //var_dump($row);
    $i=1;
    while ($row = mysqli_fetch_assoc($res)) {
        /*$_SESSION['order'][$i]['id'] = $row['id'];
        $_SESSION['order'][$i]['user_name'] = $row['user_name'];
        $_SESSION['order'][$i]['address'] = $row['address'];
        $_SESSION['order'][$i]['price'] = $row['price'];
        $_SESSION['order'][$i]['tel'] = $row['tel'];
        $_SESSION['order'][$i]['order_data'] = json_decode($row['order_data'], true);*/
        //$i++;
        $statusOrder = 'Статус заказа неопределен';

        $statusOrders = getStatus();
        foreach ($statusOrders as $key => $good) {
            if ($key == $row['status']) {
                $statusOrder = $good;
                break;
            }
        }
        $_SESSION['order'][$row['id']] = ['user_name' => $row['user_name'],
                                          'address' => $row['address'],
                                          'price' => (int)$row['price'],
                                          'tel' => $row['tel'],
                                          'order_data' => json_decode($row['order_data'], true),
                                          'status' => $statusOrder,
        ];
    }
    return orderAllAction();

}

function orderAllAction()
{
    $html = '<h2>Список заказов</h2>';
    $price = 0;
    if (empty($_SESSION['order'])) {
        $html .= "<p>Список заказов пуст</p>";
        return $html;
    }
    $html .= <<<php
php;
    $i = 1;


    foreach ($_SESSION['order'] as $key => $good) {

        $html .= <<<php
        <div>
        <a href="?p=order&a=one&id={$key}">Заказ: {$i}</a>        
php;

        if ($_SESSION['user']['role'] == '1'){
            $html .= <<<php
                <p>Пользователь: {$good['user_name']}</p>
                <p>Статус заказа: {$good['status']}</p>
php;
        }
        $html .= <<<php
        <p>Стоимость заказа: {$good['price']} рублей</p>
        <p>Адрес доставки: {$good['address']}</p>
        <p>Телефон: {$good['tel']}</p>
php;
        if ($_SESSION['user']['role'] == '1'){
            $html .= getOrderStatus($key);
        }

        $html .= <<<php
         </div>
        <hr>
php;
        $i++;
    }

    return $html;

}

function oneAction()
{
    $id = $_GET['id'];
    $sql = "SELECT id, user_name, address, price, tel, order_data, status FROM orders WHERE id='$id'";
    $res = mysqli_query(getConnect(), $sql);
    $html = '';

    $statusOrder = 'Статус заказа неопределен';


    while ($row = mysqli_fetch_assoc($res)) {
        $statusOrders = getStatus();
        foreach ($statusOrders as $key => $good) {
            if ($key == $row['status']) {
                $statusOrder = $good;
                break;
            }
        }

        if ($_SESSION['user']['role'] == '1'){
            $html .= <<<php
            <a href="#">Заказ: {$id}</a>                
                <p>Пользователь: {$row['user_name']}</p>
php;
        }
        $html .= <<<php
                <p>Статус заказа: {$statusOrder}</p>
                <p>Стоимость заказа: {$row['price']} рублей</p>
                <p>Адрес доставки: {$row['address']}</p>
                <p>Телефон: {$row['tel']}</p>
             </div>
            <hr><hr>
php;
        foreach ($_SESSION['order'][$id]['order_data'] as  $good) {

            $html .= <<<php
        <div>
            <p>Наименование: {$good['name']}</p>
            <p>Цена: {$good['price']} рублей</p>
            <p>Количество: {$good['count']}</p>
            <p>Стоимость: {$good['cost']} рублей</p>
         </div>
        <hr>
php;
        }
        if ($_SESSION['user']['role'] == '1'){
            $html .= getOrderStatus($id);
        }

    }
    return $html;
}

function addAction()
{
    $user_name = clearStr($_POST['user_name']);
    $address = clearStr($_POST['address']);
    $price = clearStr($_SESSION['basketCount']);
    $tel = clearStr($_POST['tel']);
    $order_data = json_encode($_SESSION['basket'], JSON_UNESCAPED_UNICODE);

    $sql = "
        INSERT INTO orders(user_name, address, price, tel, order_data) 
        VALUES ('$user_name', '$address', '$price', '$tel', '$order_data') 
	";

    mysqli_query(getConnect(), $sql);

    unset($_SESSION['basket']);
    unset($_SESSION['basketCount']);

    $_SESSION['msg'] = 'Заказ добавлен';

    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;

}

function getOrderStatus($id)
{
    $statusOrders = getStatus();
    $html = '';
    $html .= <<<php
    <h3>Изменить статус</h3>
    <form method="post" action="?p=order&a=changeStatus&id={$id}">
        <p><select name="status" required>
            <option>Выберите из списка</option>
php;
    foreach ($statusOrders as $key => $item) {
        $selected = '';

        if ($item == $_SESSION['order'][$id]['status']){
            $selected = 'selected';
        }

        $html .= <<<php
            <option value='{$key}' {$selected}>{$item}</option>
php;

    }
    $html .= <<<php
        </select></p>
        <button>Изменить</button>
    </form>
php;
    return $html;
}

function changeStatusAction()
{
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        header('Location: ?p=order');
        $_SESSION['msg'] = 'Что-то пошло не так';
        exit;
    }
    $id = $_GET['id'];
    $status = $_POST['status'];
    $sql = "UPDATE orders SET status='$status' WHERE id='$id'";
    mysqli_query(getConnect(), $sql);

    header('Location: ?p=order');
    $_SESSION['msg'] = 'Статус изменен';
    exit;
}

function getOrders()
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
