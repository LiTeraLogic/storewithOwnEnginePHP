<?php
session_start();

include __DIR__ . '/lib/lib.php';

$page = 'index';

$dir = __DIR__ . '/page/';
if (!empty($_GET['p'])) {
    $page = $_GET['p'];
    if (!file_exists($dir. $page . '.php')) {
        $page = 'index';
    }
}

include ($dir. $page . '.php');
$action = 'index';

if (!empty($_GET['a'])) {
    $action = $_GET['a'];
    if (!function_exists($action . 'Action')) {
        $action = 'index';
    }
}
$action .= 'Action';
$content = $action();

$html = file_get_contents(__DIR__ . '/tmpl/main.html');

$msg = '';
if (!empty($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']);
}

echo str_replace(
    ['{{CONTENT}}', '{{MSG}}', '{{MENU}}'],
    [$content, $msg, getMenu()],
    $html
);



