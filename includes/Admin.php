<?php
namespace wooBEMP;

class Admin{

    function __construct()
    {
//        include 'functions.php';
        new Admin\Menu();
        new Admin\Ajax();
        new Admin\Enque();

    }
}