<?php

function indexAction()
{
    return 'Главная ' . test();
}

function aboutAction()
{
    return 'О нас ' . test();
}

function test()
{
    return 'Страница';
}